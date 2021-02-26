function validateShowForm(){
    text = document.forms["show_form"]["show-text"].value;
    if (text.length > 50){
      alert("Text: cannot be longer then 50 characters!");
      return false;
    }
}

function validateRestartForm(){
    return confirm("Are you sure you want to submit, this will cause a restart!");
}