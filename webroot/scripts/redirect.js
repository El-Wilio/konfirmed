if( window.location.href.search("www.") == -1) {
  newUrl = "http://www.konfirmed.com" + window.location.pathname;
  location.replace(newUrl);
}