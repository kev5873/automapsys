#include <tchar.h>
#include <urlmon.h>
#include <iostream>
#include <ctime>
#include <string>
#include <sstream>

using namespace std;
#pragma comment(lib, "urlmon.lib")
int main()
{
	//function that downloads file
	HRESULT hr = URLDownloadToFile ( NULL, _T("http://www.mta.info/status/serviceStatus.txt"), _T("s\\status.txt"), 0, NULL );
	
	//rename the downloaded file into statue+unix timestamp format
	int temp = time(0);
	stringstream ss;
	ss << temp;
	string fileName = "s\\\\status-" + ss.str() + ".xml";
	char* file = new char[fileName.length()+1];

	strcpy (file, fileName.c_str());
	if(rename("s\\status.txt",file)>0)
		cout<<"done"<<endl;

	return 0;
}