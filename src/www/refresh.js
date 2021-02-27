var isTabActive;

window.onfocus = function () { 
  isTabActive = true; 
}; 

window.onblur = function () { 
  isTabActive = false; 
}; 

// test
setInterval(function () { 
  console.log(window.isTabActive ? 'active' : 'inactive'); 
}, 1000);