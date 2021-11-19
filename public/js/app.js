jQuery(
    function ($)
    {
        /* --------------- Page CREATION SORTIE ------------------ */
        //Choix vide par défaut pour les élements select
        $('#sortie_ville, #sortie_lieu, #sortie_rue, #sortie_code_postal, #sortie_latitude, #sortie_longitude')
            .prepend('<option value="" selected></option>');

        //Auto remplissage du champs "code_postal" en fonction du choix de la ville
        $(document).on('change', '#sortie_ville', function (){
            $('#sortie_code_postal').val(this.value);
        })

        //Auto remplissage des champs "rue", "latitude" et "longitude" en fonction du choix du lieu
        $(document).on('change', '#sortie_lieu', function (){
            $('#sortie_rue, #sortie_latitude, #sortie_longitude').val(this.value);
        })

        //Règle pour la date limite d'inscription, doit être au max la veille du jour de la sortie
        $(document).on('change', '#sortie_dateHeureDebut', function (){
            let date = new Date($('#sortie_dateHeureDebut').val());
            date.setDate(date.getDate() - 1);
            let formattedDate = formatDate(date);
            $('#sortie_dateLimiteInscription').removeAttr("disabled").attr({max:formattedDate, value:formattedDate});
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