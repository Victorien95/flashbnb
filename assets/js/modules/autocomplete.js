autocompletion =( function() {

    var availableTags = [
        "Accueil",
        "Connexion",
        "Annonces",
        "Inscription",
        "Créer une annonce",
        "Mes réservations",
        "Mon compte",
        "Modifier mon profil",
        "Mes favoris",
        "Modifier mon mot de passe",
        "Modifier mes informations",
        "Conditions générales des ventes",
        "CGV",
        "Qui sommes nous ?",
        "Informations FlashBnB",
        "Mentions légales",
        "S'inscrire à la newsletter",
        "Plan du site",
        "Nous contacter"
    ];


    const searchArray = {
        'accueil': '/',
        'annonces': '/ads',
        'connexion': '/login',
        'inscription': '/register',
        'créer une annonce': '/ads/new',
        'creer une annonce': '/ads/new',
        'mes réservations': '/account/bookings',
        'mes reservations': '/account/bookings',
        'mon compte': '/account',
        'modifier mon profil': '/account/profile',
        'mes favoris': '/account#liked',
        'modifier mon mot de passe': '/account/password-update',
        'modifier mes informations': '/account/profile',
        'conditions générales des ventes': '/conditions-generales-des-ventes',
        'conditions generales des ventes': '/conditions-generales-des-ventes',
        'cgv': '/conditions-generales-des-ventes',
        'qui sommes nous ?': '/qsm',
        'informations flashbnb': '/informations-flashbnb',
        'mentions légales': '/mentions-legales',
        'mentions legales': '/mentions-legales',
        "s'inscrire à la newsletter": '/newsletter',
        "plan du site": '/plan-du-site-flashbnb',
        'nous contacter': '/contact'}
    ;




    $("#searchBarre").autocomplete({
        source: availableTags,
    });



   /* $("#searchBarre" ).keydown(function (e) {
        if(e.keyCode === 13){
            var recherche = ($("#searchBarre" ).val()).toLowerCase();
            for(let i = 0; i< availableTags.length; i++){
                if(recherche === availableTags[i].toLocaleLowerCase()){
                    window.location.href = searchArray[recherche];
                }
            }
        }
    });*/

    $("#searchForm" ).submit(function (e) {
        duration = 1
        e.preventDefault()
        var recherche = ($("#searchBarre" ).val()).toLowerCase();
        for(var i = 0; i< availableTags.length; i++){
            //console.log(recherche)
            //console.log(availableTags[i].toLocaleLowerCase() + 'ICI')
            if(recherche === availableTags[i].toLocaleLowerCase()){
                window.location.href = searchArray[recherche];
                return
            }
        }
        if (i  === availableTags.length){
            $( "#searchForm" ).effect( "shake", {times: 3, distance: 10})
            $( "#searchBarre" ).effect("shake", {times: 3, distance: 10}).css({border: 'solid 3px rgba(255,0,0, 0.3)', borderRadius:'0.25rem'})
            $( "#searchButton" ).effect( "shake", {times: 3, distance: 10}).css({backgroundColor: 'rgba(255,0,0, 0.6)', border:'none', color:'white'})
            //$("#searchBarre" ).effect("c").css('border', 'solid red 3px')
        }
    });

})();