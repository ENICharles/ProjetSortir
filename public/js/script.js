
function selectDate()
{
    document.getElementById('dateStart').disabled = !document.getElementById('dateStart').disabled;
    document.getElementById('dateEnd').disabled = !document.getElementById('dateEnd').disabled;
}

function getDataLocalisation()
{
    let nomLieu = document.getElementById('event_localisation_name').value;
    let req  = "";

    // alert('hgfkhfg');

    $(document).ready(function ()
    {
        $.ajax(
            {
                url: "/event/getDataLocalisation",
                method: "GET",
                dataType: "json",
            })

            .done(function(response)
            {
                let data = JSON.parse(response);
                //let data = JSON.stringify(response);

                console.log(response);
                // document.getElementById('event_description').value = data[0].name;
            })

            .fail(function(error)
            {
                //alert("pas de retour du serveur");
            });
    });
}