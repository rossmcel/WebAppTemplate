
function showUrl() {
  document.getElementById("myUrl-drop").classlist.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('showmyUrlbtn')) {
    var dropdowns = document.getElementsByClassName("myUrl-drop-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}