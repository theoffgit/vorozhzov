window.addEventListener("load", function(){
    if (document.getElementById('button') !== null) {
        const btn = document.getElementById('button');

        btn.addEventListener("click", async function () {
            const id = document.getElementById('id').value;
            const token = document.getElementById('usertoken').value;
            const data = document.getElementById('data').value;
            const reqType = document.getElementById('reqType').checked;
            const csrf = document.getElementsByName('_token')[0].value;

            let resp;
            if(document.getElementById('reqType').checked){  // POST
                const body = {
                    id: id,
                    _token: csrf,
                    data: data,
                };

                resp = await fetch('/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json;charset=utf-8',
                        Authorization: 'Bearer '+token,
                        Accept: 'application/json'
                    },
                    body: JSON.stringify(body)
                });
            }else{
                resp = await fetch('/update?'+new URLSearchParams({
                        id: id,
                        _token: csrf,
                        data: data,
                    }),
                    {
                        headers: {
                            Authorization: 'Bearer '+token,
                            Accept: 'application/json'
                        }
                    }
                );
            }
            const respJson = await resp.json();
            if(respJson.message){
                const mess = await respJson.message;
                console.log(respJson.message);
                const elem = document.getElementById("message");
                elem.innerHTML = respJson.message;
            }
        });
    }
});
