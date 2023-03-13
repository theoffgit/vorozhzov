window.addEventListener("load", function(){
    var usertoken = '';
    const messelem = document.getElementById("message");

    if (document.getElementsByClassName('delete') !== null) {

        document.addEventListener("contextmenu", function (e){
            e.preventDefault();
        }, false);

        const links = document.getElementsByClassName('delete');

        var myFunction = async function(elem) {
            elem.preventDefault();
            let url = this.href;
            if(!usertoken){
                usertoken = await prompt('Token', '');
            }
            let resp = await fetch(url, {
                headers: {
                    Authorization: 'Bearer '+usertoken,
                    Accept: 'application/json'
                }
            });
            const respJson = await resp.json();
            if(respJson.message){
                console.log(respJson);
                const mess = await respJson.message;
                messelem.innerHTML = respJson.message;
                if(respJson.message == 'Unauthenticated.'){
                    usertoken = '';
                }
            }
            return false;
        };

        //for (var i = 0; i < links.length; i++) {
        //    links[i].addEventListener('click', myFunction, false);
        //}
        Array.from(links).forEach(function(link) {
            link.addEventListener('click', myFunction);
        });
    }
});