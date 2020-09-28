function handleEvent() {
  let number = document.getElementById("number").value;

  var req = new XMLHttpRequest();
  req.overrideMimeType("application/json");
  req.open("GET", `./api/script.php?number=${number}`, true);
  req.onload = function () {
    var jsonData = JSON.parse(req.responseText);

    if (req.readyState == 4) {
      if (req.status == 200) {
        document.getElementById("error").innerHTML = "";
        document.getElementById("word").innerHTML = jsonData.value;
      }
      if (this.status == 422) {
        document.getElementById("word").innerHTML = "";
        document.getElementById("error").innerHTML = jsonData.message.number;
      }
    }
  };
  req.send(null);
}
