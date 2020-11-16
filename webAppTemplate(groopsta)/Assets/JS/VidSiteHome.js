
function getLocation() {
  chrome.storage.local.get(['location']);
  console.log('location');
  return location;
  if(location == null) {
    return 0;
  }
}



