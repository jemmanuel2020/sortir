jQuery(
    function ($)
    {
        //Choix vide par défaut pour les élements select - PAGE CREATION SORTIE
        $('#create-sortie-page #sortie_ville, ' +
            '#create-sortie-page #sortie_lieu, ' +
            '#create-sortie-page #sortie_rue, ' +
            '#create-sortie-page #sortie_code_postal, ' +
            '#create-sortie-page #sortie_latitude, ' +
            '#create-sortie-page #sortie_longitude')
            .prepend('<option value="" selected></option>');

        //Evenement 'on change' AJAX
        $(document).on('change', '#sortie_ville', function () {
            //var nomVilleSelectionnee = $('#sortie_ville option:selected').text();
            $("#sortie_ville option[value='']").remove();

            // Requête les lieux de la ville sélectionnée.
            $.ajax({
                //url: window.location.href.replace('create', 'listLieux'),
                url: 'http://localhost:8080/sortir/public/sortie/listLieux',
                type: "GET",
                dataType: "JSON",
                data: {
                    ville_id: $(this).val()
                },
                success: function (lieux) {
                    // Remove current options
                    $('#sortie_lieu, #sortie_rue, #sortie_latitude, #sortie_longitude').html('');

                    // If empty value ...
                    if (lieux == "")
                        $("#sortie_lieu").attr('disabled', 'disabled');
                    else
                    {
                        $("#sortie_lieu").removeAttr('disabled');
                        $.each(lieux, function (key, lieu) {
                            $("#sortie_lieu").append('<option value="' + lieu.id_lieu + '">' + lieu.nom + '</option>');
                            $("#sortie_rue").append('<option value="' + lieu.id_lieu + '">' + lieu.rue + '</option>');
                            $("#sortie_latitude").append('<option value="' + lieu.id_lieu + '">' + lieu.latitude + '</option>');
                            $("#sortie_longitude").append('<option value="' + lieu.id_lieu + '">' + lieu.longitude + '</option>');
                        });
                    }
                },
                error: function (err) {
                    alert("An error ocurred while loading data ...");
                }
            });
        });

        //Auto remplissage du champs "code_postal" en fonction du choix de la ville
        $(document).on('change', '#sortie_ville', function (){
            $('#sortie_code_postal').val(this.value);
        })

        //Règle pour la date limite d'inscription, doit être au max la veille du jour de la sortie
        $(document).on('change', '#sortie_dateHeureDebut', function (){
            let date = new Date($(this).val());
            date.setDate(date.getDate() - 1);
            let formattedDate = formatDate(date);
            $('#sortie_dateLimiteInscription').attr({max:formattedDate, value:formattedDate}).val(formattedDate);
        })

        /* ---------------  ------------------ */

        function formatDate(date)
        {
            let day = date.getDate();
            if(day >=1 && day <= 9)
                day = "0" + day;
            let month = date.getMonth() + 1;
            if(month >=1 && month <= 9)
                month = "0" + month;
            let year = date.getFullYear();

            return year + "-" + month + "-" + day;
        }
    }
);