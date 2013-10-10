<?php
class JasperApi {
  // $r = new JasperApi('path/to/report', format, parameters)
  // $r->getReport() get content for streaming to a file or the web.
  private $_js_api_reports = "http://192.168.0.145:8080/jasperserver/rest_v2/reports/";
  private $_report_content;
  private $_path_to_report;
  private $_format;
  private $_parameters;
  private $_uri;

  public function __construct($path, $format = "pdf", $parameters = ""){
    $this->_path_to_report = $path;
    $this->_format = $format;
    $this->_parameters = $parameters;
    $this->getRun();
  }
  public function reRun(){
    $this->getRun();
  }

  public function getReport(){
    return $this->_report_content;
  }

  public function getURI(){
    return $this->buildUri();
  }

  public function getFormat(){
    return $this->_format;
  }

  public function setFormat($format){
    $this->_format = $format;
  }

  public function getParameters(){
    return $this->_parameters;
  }

  public function setParameters($parameters){
    $this->_parameters = $parameters;
  }

  private function buildUri(){
    $this->_uri = $this->_js_api_reports.$this->_path_to_report.".".$this->_format;
    if($this->_parameters != "")
      $this->_uri = $this->_uri."?".$this->_parameters;
    return $this->_uri;
  }

  private function getRun(){
    $uri = $this->buildUri();
    $request = curl_init($uri);
      curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($request, CURLOPT_USERPWD, "jasperadmin:jasperadmin");
      curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
      $response = curl_exec($request);
    curl_close($request);
    $this->_report_content = $response;
    return $this;
  }
}