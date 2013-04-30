#include <iostream>
#include <string>
#include <curl/curl.h>
#include <stdio.h>
#include <sstream>

size_t write_data(void *ptr, size_t size, size_t nmemb, FILE *stream) {
    size_t written;
    written = fwrite(ptr, size, nmemb, stream);
    return written;
}

int main() {
    std::string tempname;
    tempname.append("status-");
    std::time_t t = std::time(0);
    std::stringstream strm;
    strm << t;
    tempname.append(strm.str());
    tempname.append(".txt");
    CURL *curl;
    CURLcode res;
    curl = curl_easy_init();
    if(curl) {
        FILE *fp = fopen(tempname.c_str(),"wb");
        curl_easy_setopt(curl, CURLOPT_URL, "http://www.mta.info/status/serviceStatus.txt");
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, write_data);
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
        res = curl_easy_perform(curl);
        curl_easy_cleanup(curl);
        fclose(fp);
        fp = fopen(tempname.c_str(),"rb");
        fseek (fp , 0 , SEEK_END);
        long lSize = ftell (fp);
        rewind(fp);
        char *buffer = new char[lSize+1];
        fread (buffer, 1, lSize, fp);
        buffer[lSize] = 0;
        fclose(fp);
        std::string content(buffer);
        delete [] buffer;
    }
    
}