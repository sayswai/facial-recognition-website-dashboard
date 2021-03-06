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

int main( int argc, char* argv[] ){
  char* vID;
  if(argc > 1) {
    vID = argv[1];
  }
  else{
    std::cout << "Not enough arguments";
    exit(1);
  }


  int i = 1;
  char* urr;
  strcpy(urr, "/vids/");
  strcat(urr, vID);
  strcat(urr, "/done_openface");
  int max = 0;
  while(i<max || max==0){
    if(std::ifstream(urr) && max==0) {
      char* urt;
      strcpy(urt, "/vids/");
      strcat(urt, vID);
      strcat(urt, "/detected_frames/");
      max = std::count_if(boost::filesystem::directory_iterator(urt), boost::filesystem::directory_iterator(), static_cast<bool(*)(const boost::filesystem::path&)>(boost::filesystem::is_regular_file));
    }
    if (i>max && max != 0){
      break;
    }


    PGresult *pgres2;

    //Make i easily usable
    std::string s = std::to_string(i);
    char const* fnum = s.c_str();

    //Set up query for current frame number
    char *pg_ofquery;
    char *pg_iquery;
    strcpy(pg_ofquery, "SELECT * FROM openface WHERE framenum = ");
    strcat(pg_ofquery, fnum);
    strcat(pg_ofquery, " AND vid = ");
    strcat(pg_ofquery, vID);
    strcpy(pg_iquery, "SELECT rightpupilx, rightpupily, leftpupilx, leftpupily FROM eye WHERE framenum = ");
    strcat(pg_iquery, fnum);
    strcat(pg_iquery, " AND vid = ");
    strcat(pg_iquery, vID);

    //Execute queries, wait up to a day if current frame not stored in either database table
    pgres = PQexec(pgconn, pg_ofquery);
    int c = 0;
    while(PQresultStatus(pgres) != PGRES_TUPLES_OK && c<qcount){
      PQclear(pgres);
      usleep(slplen);
      pgres = PQexec(pgconn, pg_ofquery);
      c++;
    }
    if(PQresultStatus(pgres) != PGRES_TUPLES_OK){
      fprintf(stderr, "Query on database connection failed: %s", PQerrorMessage(pgconn));
      PQclear(pgres);
      connection_exit(pgconn);
    }

    pgres2 = PQexec(pgconn, pg_iquery);
    c = 0;
    while(PQresultStatus(pgres2) != PGRES_TUPLES_OK && c<qcount){
      PQclear(pgres2);
      usleep(slplen);
      pgres2 = PQexec(pgconn, pg_iquery);
      c++;
    }
    if(PQresultStatus(pgres2) != PGRES_TUPLES_OK){
      fprintf(stderr, "Query on database connection failed: %s", PQerrorMessage(pgconn));
      PQclear(pgres2);
      connection_exit(pgconn);
    }

    //Create padded frame number for file navigation
    char *nav;
    if(i<10){
      strcpy(nav, "000");
      strcat(nav, fnum);
    }
    else if(i<100){
      strcpy(nav, "00");
      strcat(nav, fnum);
    }
    else if(i < 1000){
      strcpy(nav, "0");
      strcat(nav, fnum);
    }
    else {
      strcpy(nav, fnum);
    }

    //Get image and define space to partition into triangles
    char *imgurl;
    strcpy(imgurl, "/var/www/html/vids/");
    strcat(imgurl, vID);
    strcat(imgurl, "/split_");
    strcat(imgurl, nav);
    strcat(imgurl, ".png");
    cv::Mat img_original = cv::imread(imgurl);
    cv::Rect space = cv::Rect(0,0,width,height);

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

    //Create subdiv2d with area defined above
    cv::Subdiv2D sdiv = cv::Subdiv2D(space);

    //Extract points from result of openface Query and store in sdiv. Draw dot on image for each point
    std::vector<cv::Point2f> points;
    for(int j=0; j<PQnfields(pgres); j++){
      cv::Point2f p = cv::Point2f(PQgetvalue(pgres, 0, j)[0], PQgetvalue(pgres, 0, j)[1]);
      sdiv.insert(p);
      dot(img_original, p);
    }

    //Draw triangles on image
    triangles(img_original, sdiv, width, height);

    //Write image to new file
    char *url;
    strcpy(url, "/var/www/html/vids/");
    strcat(url, vID);
    strcat(url, "/detected_frames/");
    strcat(url, fnum);
    strcat(url, ".png");
    cv::imwrite(url, img_original);

  }
  PQfinish(pgconn);
}
