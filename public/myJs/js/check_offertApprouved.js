function checkNotifications() {
    $.ajax({
        url: "/admin/offert/notifications/check",
        method: "GET",
        success: function (notifications) {
            if (notifications.length > 0) {
                notifications.forEach(function (notification) {
                    // Swal.fire({
                    //     icon: 'info',
                    //     title: 'Nouvelle notification',
                    //     html: notification.message,
                    //     showCancelButton: true,
                    //     cancelButtonText: 'Fermer',
                    //     confirmButtonText: '<a href="/admin/vente/show/' +
                    //         notification.vente_id +
                    //         '" style="color:white;text-decoration:none;">Voir la vente</a>'
                    // });

                    // 🔊 Joue le son
                    // Remplace ce bloc
                    // Swal.fire({ ... });

                    const alertContainer =
                        document.getElementById("alert-ApprouvedOffert");

                    const alertDiv = document.createElement("div");
                    alertDiv.className =
                        "alert alert-success alert-dismissible alert-label-icon label-arrow fade show material-shadow";
                    alertDiv.setAttribute("role", "alert");

                    alertDiv.innerHTML = `
                            <i class="ri-notification-3-line label-icon"></i>
                            <strong>Nouvelle notification :</strong> ${notification.message}
                            <a href="/admin/vente/show/${notification.vente_id}" class="btn btn-sm btn-info ms-2">Voir la vente</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;

                    alertContainer.appendChild(alertDiv);

                    // 🔊 Son de notification (facultatif)
                    const alertSound = new Audio("/sounds/sound1.mp3");
                    alertSound.play();

                    // ⏱️ Auto-fermeture après 5 secondes
                    setTimeout(() => {
                        const alert =
                            bootstrap.Alert.getOrCreateInstance(alertDiv);
                        alert.close();
                    }, 25000);


                    // Marquer la notification comme lue
                    $.ajax({
                        url: "{{ route('notifications.markAsRead') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: notification.id,
                        },
                        success: function (response) {
                            console.log(
                                "Notification " +
                                    notification.id +
                                    " marquée comme lue."
                            );
                        },
                        error: function (xhr) {
                            console.error(
                                "Erreur lors de la lecture de la notification."
                            );
                        },
                    });
                });
            }
        },
        error: function (xhr) {
            console.error("Erreur lors de la récupération des notifications.");
        },
    });
}

// Vérifie les notifications toutes les 10 secondes
setInterval(checkNotifications, 30000);