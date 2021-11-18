jQuery(
    function ($)
    {
        //Page création sortie - choix vide par défaut pour les élements select
        $('#sortie_ville').prepend('<option value="" selected></option>');
        $('#sortie_lieu').prepend('<option value="" selected></option>');
        $('#sortie_rue').prepend('<option value="" selected></option>');
        $('#sortie_code_postal').prepend('<option value="" selected></option>');
        $('#sortie_latitude').prepend('<option value="" selected></option>');
        $('#sortie_longitude').prepend('<option value="" selected></option>');

        $(document).on('change', '#sortie_ville', function (){
            $('#sortie_code_postal').val(this.value);
        })

        $(document).on('change', '#sortie_lieu', function (){
            $('#sortie_rue').val(this.value);
            $('#sortie_latitude').val(this.value);
            $('#sortie_longitude').val(this.value);
        })
    }
);