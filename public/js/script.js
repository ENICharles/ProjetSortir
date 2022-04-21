
function selectDate()
{
    document.getElementById('dateStart').disabled = !document.getElementById('dateStart').disabled;
    document.getElementById('dateEnd').disabled = !document.getElementById('dateEnd').disabled;
}

function getDataLocalisation(id)
{
  $(document).ready(function ()
    {

        $.ajax(
            {
                url: "/localisation/getInfo/" + id,
                method: "GET",
            })

            .done(function(response)
            {
                let data = JSON.parse(response);

                document.getElementById('info_lieu').innerHTML =
                    '<ul>' +
                    '<li><label>Rue : ' + data.street + '</label></li>' +
                    '<li><label>Code postal : ' + data.city.postcode + '</label></li>' +
                    '<li><label>Ville : ' + data.city.name + '</label></li>' +
                    '<li><label>Latitude : ' + data.latitude + '</label></li>' +
                    '<li><label>Longitude : ' + data.longitude + '</label></li>' +
                    '</ul> ';
            })

            .fail(function(error)
            {
                alert("pas de retour du serveur");
            });
    });
}


function show(){
    getDataLocalisation(document.getElementById('event_localisation').value);
}
