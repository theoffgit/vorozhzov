import JSONFormatter from "json-formatter-js";

window.addEventListener("load", function(){
    const formatter = new JSONFormatter(myJson);
    document.body.appendChild(formatter.render());
});
