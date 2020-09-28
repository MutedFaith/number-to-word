function handleEvent() {
  let number = document.getElementById("number").value;

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    const data = JSON.parse(this.responseText);

    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("error").innerHTML = "";
      document.getElementById("word").innerHTML = data.value;
    }

    if (this.readyState == 4 && this.status == 422) {
      document.getElementById("word").innerHTML = "";
      document.getElementById("error").innerHTML = data.message.number;
    }
  };
  xhttp.open("GET", `./api/script.php?number=${number}`, true);
  xhttp.send();
}
