#include <stdio.h>
#include <stdlib.h>
#include <string>
#include <sstream>
#include <opencv2/imgproc/imgproc.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <iostream>
#include <fstream>
#include <libpq-fe.h>


const int qcount = 1440; //Number of times to attempt each query (qcount*slplen=max_execution_time)
const int slplen = 60000; //Sleep length between each attempt


//Util: End connection, then exit
static void connection_exit(PGconn *pgconn){
  PQfinish(pgconn);
  exit(1);
}

//Util: Draw a point
static void dot(Mat& image, Point2f ref){
  Scalar color = Scalar(223, 61, 130);
  circle(image, ref, 2, color, CV_FILLED, CV_AA, 0);
}

//Util: Draw all triangles
static void triangles(Mat& image, Subdiv2D& sdiv, int width, int height){
  Scalar color = Scalar(223, 61, 130);

  //Get list of triangles to draw from sdiv into triangles, and define workspace
  vector<Vec6f> triangles;
  vector<Point> tripoints(3);
  sdiv.getTriangleList(triangles);
  Rect space = Rect(0,0,width,height);

  //Draw every triangle
  for(int i=0; i<triangles.size(); i++){
    Vec6f tri = triangles[i];
    tripoints[0] = Point(cvRound(tri[0]), cvRound(tri[1]));
    tripoints[1] = Point(cvRound(tri[2]), cvRound(tri[3]));
    tripoints[2] = Point(cvRound(tri[4]), cvRound(tri[5]));

    //Ensure triangles are inside image
    if(space.contains(tripoints[0]) && space.contains(tripoints[1]) && space.contains(tripoints[2])){
      line(img, tripoints[0], tripoints[1], color, 1, CV_AA, 0);
      line(img, tripoints[1], tripoints[2], color, 1, CV_AA, 0);
      line(img, tripoints[2], tripoints[0], color, 1, CV_AA, 0);
    }
  }
}

int main( int argc, const char** argv ){
  const char *vID = argv[1];

  const char *pginfo;
  const char *pg_vidquery;
  PGconn *pgconn;
  PGresult *pgres;

  //DB connect and query info
  pginfo = "dbname=CS160 host=localhost port=5432 user=postgres password=1";
  pg_vidquery = "SELECT framecount, width, height FROM (SELECT * FROM videos WHERE vid = "+ vID +")";


  //Start and test connection
  pgconn = PQconnectdb(pginfo);
  if(PQstatus(pgconn) != CONNECTION_OK){
    fprintf(stderr, "Connection to postgres failed: %s", PQerrorMessage(pgconn));
    connection_exit(pgconn);
  }

  //Execute query on db and check result
  pgres = PQexec(pgconn, pg_vidquery);
  if(PQresultStatus(pgres) != PGRES_TUPLES_OK){
    fprintf(stderr, "Query on database connection failed: %s", PQerrorMessage(pgconn));
    PQclear(pgres);
    connection_exit(pgconn);
  }

  //Get values from result, and clear result
  const int fcount = static_cast<int>(PQgetvalue(pgres, 0, 0));
  const int width = static_cast<int>(PQgetvalue(pgres, 0, 1));
  const int height = static_cast<int>(PQgetvalue(pgres, 0, 2));
  PQclear(pgres);

  for(int i=1; i<=fcount; i++){
    PGresult *pgres2;

    //Make i easily usable
    std::ostringstream oss;
    oss << i;
    char *fnum = oss.str();

    //Set up query for current frame number
    char *pg_ofquery;
    char *pg_iquery;
    pg_ofquery = "SELECT * FROM openface WHERE framenum = " + fnum;
    pg_iquery = "SELECT rightpupilx, rightpupily, leftpupilx, leftpupily FROM (SELECT * FROM eye WHERE framenum = "+ fnum +")";

    //Execute queries, wait up to a day if current frame not stored in either database table
    pgres = PQexec(pgconn, pg_ofquery);
    int c = 0;
    while(PQresultStatus(pgres) != PGRES_TUPLES_OK && c<qcount){
      PQclear(pgres);
      sleep(slplen);
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
      sleep(slplen);
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
    switch(fnum){
      case (fnum<10):
        nav = "000"+fnum;
      case (fnum<100):
        nav = "00"+fnum;
      case (fnum<1000):
        nav = "0"+fnum;
      default:
        nav = fnum;
    }

    //Get image and define space to partition into triangles
    Mat img_original = imread("/var/www/html/vids/"+ vID +"/split_"+ nav +".png");
    Rect space = Rect(0,0,width,height);

    //Get pupil x and y, and draw dots on them
    Point2f pupilRight = Point2f(PQgetvalue(pgres2, 0, 0), PQgetvalue(pgres2, 0, 1));
    Point2f pupilLeft = Point2f(PQgetvalue(pgres2, 0, 2), PQgetvalue(pgres2, 0, 3));
    dot(img_original, pupilRight);
    dot(img_original, pupilLeft);

    //Create subdiv2d with area defined above
    sdiv = Subdiv2D subdiv(space);

    //Extract points from result of openface Query and store in sdiv. Draw dot on image for each point
    vector<Point2f> points;
    for(int j=0; j<PQnfields(pgres); j++){
      Point2f p = Point2f(PQgetvalue(pgres, 0, j)[0], PQgetvalue(pgres, 0, j)[1]);
      sdiv.insert(p);
      dot(img_original, p);
    }

    //Draw triangles on image
    triangles(img, sdiv, width, height);

    //Write image to new file
    imwrite("/var/www/html/vids/" + vID +"/detected_frames/" + fnum + ".png");

  }
  PQfinish(pgconn);
}
