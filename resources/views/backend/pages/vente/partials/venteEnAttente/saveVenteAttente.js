$('#save-sale-in-local').click(function () {
    const venteTemp = {
        cart: cart,
        tableNumber: $('#table-number').val(),
        covers: $('#number-covers').val(),
        date: new Date().toISOString()
    };

    let ventes = JSON.parse(localStorage.getItem('ventes_en_attente')) || [];

    ventes.push({
        id: Date.now(),
        data: venteTemp
    });

    localStorage.setItem('ventes_en_attente', JSON.stringify(ventes));

    // Réinitialiser l'interface
    cart = [];
    updateCartTable();
    updateGrandTotal();
    $('#table-number').val('');
    $('#number-covers').val(1);

    Swal.fire({
        icon: 'info',
        title: 'Vente mise en attente',
        text: 'Commencez une nouvelle vente.'
    });

    // Mise à jour de l'affichage des ventes en attente
    afficherVentesLocales();
});
