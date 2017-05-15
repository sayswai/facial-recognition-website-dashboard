#include <stdio.h>
#include <stdlib.h>
#include <string>
#include <sstream>
#include <opencv2/imgproc/imgproc.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <iostream>
#include <fstream>
#include <unistd.h>
#include </usr/include/boost/filesystem.hpp>
#include </usr/include/boost/lambda/bind.hpp>
#include "/usr/include/string.h"
#include "/usr/include/postgresql/libpq-fe.h"


const int qcount = 1440; //Number of times to attempt each query (qcount*slplen=max_execution_time)
const int slplen = 60000; //Sleep length between each attempt


//Util: End connection, then exit
static void connection_exit(PGconn *pgconn){
  PQfinish(pgconn);
  exit(1);
}

//Util: Draw a point
static void dot(cv::Mat& image, cv::Point2f ref){
  cv::Scalar color = cv::Scalar(223, 61, 130);
  circle(image, ref, 2, color, CV_FILLED, CV_AA, 0);
}

//Util: Draw all triangles
static void triangles(cv::Mat& image, cv::Subdiv2D& sdiv, int width, int height){
  cv::Scalar color = cv::Scalar(223, 61, 130);

  //Get list of triangles to draw from sdiv into triangles, and define workspace
  std::vector<cv::Vec6f> triangles;
  std::vector<cv::Point> tripoints;
  sdiv.getTriangleList(triangles);
  cv::Rect space = cv::Rect(0,0,width,height);

  //Draw every triangle
  for(int i=0; i<triangles.size(); i++){
    cv::Vec6f tri = triangles[i];
    tripoints[0] = cv::Point(cvRound(tri[0]), cvRound(tri[1]));
    tripoints[1] = cv::Point(cvRound(tri[2]), cvRound(tri[3]));
    tripoints[2] = cv::Point(cvRound(tri[4]), cvRound(tri[5]));

    //Ensure triangles are inside image
    if(space.contains(tripoints[0]) && space.contains(tripoints[1]) && space.contains(tripoints[2])){
      cv::line(image, tripoints[0], tripoints[1], color, 1, CV_AA, 0);
      cv::line(image, tripoints[1], tripoints[2], color, 1, CV_AA, 0);
      cv::line(image, tripoints[2], tripoints[0], color, 1, CV_AA, 0);
    }
  }
}

int main(int argc, char* argv[]){
  printf("Started \n");
  char* vID = new char[100];
  char* fnum = new char[20];
  if(argc>2){
    strcpy(vID, argv[1]);
    printf("Video containing image: %s\n", vID);
    strcpy(fnum, argv[2]);
    printf("Frame number of video: %s\n", fnum);
    printf("Begin processing frame %s from video %s\n", fnum, vID);
  }
  else{
    printf("Not enough args, needs ./output vID fnum\n");
    exit(1);
  }

  //Vars for db access
  printf("Prior to pginfo\n");
  const char *pginfo;
  pginfo = "host=localhost port=5432 dbname=CS160 user=postgres password=umyserver";
  printf("PGinfo retrieved: %s\n", pginfo);
  PGconn *pgconn;
  PGresult *pgres;
  PGresult *pgres2;
  printf("Variables prepared for DB connection\n");

  //Start and test connection
  int conncount = 0;
  try{
    pgconn = PQconnectdb(pginfo);
  }
  catch(std::exception& e1){
    sleep(1000);
    printf("Attempt %i failed, reattempting...\n", conncount);
    while(PQstatus(pgconn) != CONNECTION_OK || conncount > qcount){
      try{
        conncount++;
        pgconn = PQconnectdb(pginfo);
      }
      catch(std::exception& e2){
        sleep(1000);
        printf("Attempt %i failed, reattempting...\n", conncount);
      }
    }
  }
  if(PQstatus(pgconn) != CONNECTION_OK){
    printf("Connection to postgres failed: %s\n", PQerrorMessage(pgconn));
    connection_exit(pgconn);
  }
  printf("DB connection successfully established\n");

  //Set up query for current frame number in eye and openface tables
  char* pg_ofquery = new char[100];
  char* pg_iquery = new char[100];
  strcpy(pg_ofquery, "SELECT * FROM openface WHERE vid = ");
  strcat(pg_ofquery, vID);
  strcat(pg_ofquery, " AND framenum = ");
  strcat(pg_ofquery, fnum);
  printf("Openface query created successfully: %s\n", pg_ofquery);
  strcpy(pg_iquery, "SELECT rightpupilx, rightpupily, leftpupilx, leftpupily FROM eye WHERE vid = ");
  strcat(pg_iquery, vID);
  strcat(pg_iquery, " AND framenum = ");
  strcat(pg_iquery, fnum);
  printf("Eye query created successfully: %s\n", pg_ofquery);

  //Get and test result
  pgres = PQexec(pgconn, pg_ofquery);
  if(PQresultStatus(pgres) != PGRES_TUPLES_OK){
    printf("Query on database connection failed: %s\n", PQerrorMessage(pgconn));
    PQclear(pgres);
    connection_exit(pgconn);
  } else if (PQntuples(pgres) == 0){
    printf("No OpenFace Points\n");
    connection_exit(pgconn);
  }
  printf("Openface query successful\n");

  pgres2 = PQexec(pgconn, pg_iquery);
  if(PQresultStatus(pgres2) != PGRES_TUPLES_OK){
    printf("Query on database connection failed: %s\n", PQerrorMessage(pgconn));
    PQclear(pgres2);
    connection_exit(pgconn);
  } else if (PQntuples(pgres2) == 0){
    printf("No Eye Points\n");
    connection_exit(pgconn);
  }
  printf("Eye query successful\n");

  //Create padded frame number for file navigation
  char* nav;
  int h = std::atoi(fnum);
  if(h<10){
    strcpy(nav, "000");
    strcat(nav, fnum);
  }
  else if(h<100){
    strcpy(nav, "00");
    strcat(nav, fnum);
  }
  else if(h<1000){
    strcpy(nav, "0");
    strcat(nav, fnum);
  }
  else {
    strcpy(nav, fnum);
  }
  printf("Padded framenum successful\n");

  //Get image and define space to partition into triangles
  char* imgurl = new char[100];
  strcpy(imgurl, "/var/www/html/vids/");
  strcat(imgurl, vID);
  strcat(imgurl, "/split_");
  strcat(imgurl, nav);
  strcat(imgurl, ".png");
  cv::Mat img_original = cv::imread(imgurl);
  cv::Rect space = cv::Rect(0,0,img_original.size().width-1,img_original.size().height-1);
  printf("Opencv prep (imgurl, imread, rect space) successful\n");

  //Get pupil x and y, and draw dots on them
  std::string rnx(PQgetvalue(pgres2, 0, 0));
  std::string rny(PQgetvalue(pgres2, 0, 1));
  std::string lnx(PQgetvalue(pgres2, 0, 2));
  std::string lny(PQgetvalue(pgres2, 0, 3));
  float rx = std::stof(rnx);
  float ry = std::stof(rny);
  float lx = std::stof(lnx);
  float ly = std::stof(lny);
  cv::Point2f pupilRight = cv::Point2f(rx, ry);
  cv::Point2f pupilLeft = cv::Point2f(lx, ly);
  dot(img_original, pupilRight);
  dot(img_original, pupilLeft);
  printf("Eye prep and pupil dots successful\n");

  //Create subdiv2d with area defined above
  cv::Subdiv2D sdiv = cv::Subdiv2D(space);
  printf("Subdiv2D successfully constructed\n");

  //Extract points from result of openface Query and store in sdiv. Draw dot on image for each point
  std::vector<cv::Point2f> points;
  for(int j=0; j<PQnfields(pgres); j++){
    printf("PQvalue: %s", PQgetvalue(pgres, 0, j));
    cv::Point2f p = cv::Point2f(PQgetvalue(pgres, 0, j)[0], PQgetvalue(pgres, 0, j)[1]);
    sdiv.insert(p);
    dot(img_original, p);
  }
  printf("Opencv table point extraction and dot draw successful\n");

  //Draw triangles on image
  triangles(img_original, sdiv, img_original.size().width, img_original.size().height);
  printf("Triangle draw successful\n");

  //Write image to new file
  char* url = new char[100];
  strcpy(url, "/var/www/html/vids/");
  strcat(url, vID);
  strcat(url, "/detected_frames/");
  strcat(url, fnum);
  strcat(url, ".png");
  cv::imwrite(url, img_original);
  PQfinish(pgconn);
  printf("Image write and dbfinish successful\n");
}
