// Table pour stocker les IDs déjà vus
let lastIds = [];
function checkOfferts() {
    $.ajax({
        url: "/admin/offert/getOffertNoApprouved",
        type: "GET",
        success: function (response) {
            const offerts = response.offerts;

            const newItems = offerts.filter((o) => !lastIds.includes(o.id));

            // if (newItems.length > 0) {
            //     Swal.fire({
            //         position: "center",
            //         icon: "warning",
            //         title:
            //             "Vous avez " +
            //             newItems.length +
            //             " offerts non approuvés",
            //         showConfirmButton: false,
            //         timer: 5000,
            //     });
            // }

            // // Ajouter les nouveaux offerts au tableau uniquement s'ils ne sont pas déjà dans le DOM
            // newItems.forEach(item => {
            //     if (!document.getElementById('row_' + item.id)) {
            //         // Génère l'URL de base via Laravel (sans paramètres)
            //         const baseApproveUrl = "{{ route('offert.approuvedOffert') }}";

            //         const approveUrl =
            //             `${baseApproveUrl}?offert=${item.id}&approuved=1`;
            //         const rejectUrl =
            //             `${baseApproveUrl}?offert=${item.id}&approuved=0`;

            //         $('#buttons-datatables tbody').prepend(`
            //                 <tr id="row_${item.id}">
            //                     <td></td>
            //                     <td>
            //                         ${item.offert_statut === null
            //                             ? '<span class="badge bg-warning">En attente</span>'
            //                             : item.offert_statut ===0
            //                                 ? '<span class="badge bg-success">Approuvé</span>'
            //                                 : '<span class="badge bg-danger">Rejeté</span>'
            //                         }
            //                     </td>
            //                     <td>${item.vente.code}</td>
            //                     <td>
            //                         ${item.produit.nom} * ${item.quantite} ${item.variante.libelle} de ${item.prix}
            //                     </td>
            //                     <td>
            //                         ${item.vente.user.first_name} - ${item.vente.caisse?.libelle ?? 'N/A'}
            //                     </td>
            //                     <td>
            //                         ${item.date_created ? new Date(item.date_created).toLocaleDateString('fr-FR', {
            //                             day: '2-digit',
            //                             month: '2-digit',
            //                             year: 'numeric',
            //                             hour: '2-digit',
            //                             minute: '2-digit'
            //                         }) : ''}
            //                     </td>
            //                     <td class="d-block">
            //                         <div class="dropdown d-inline-block">
            //                             <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
            //                                 data-bs-toggle="dropdown" aria-expanded="false">
            //                                 <i class="ri-more-fill align-middle"></i>
            //                             </button>
            //                             <ul class="dropdown-menu dropdown-menu-end">
            //                                 ${item.offert_statut === null ? `
            //                                                             <li>
            //                                                                 <a href="${approveUrl}" class="dropdown-item remove-item-btn" data-id="${item.id}">
            //                                                                     <i class="ri-check-line align-bottom me-2 text-muted"></i>
            //                                                                     Approuver
            //                                                                 </a>
            //                                                             </li>
            //                                                             <li>
            //                                                                 <a href="${rejectUrl}" class="dropdown-item remove-item-btn" data-id="${item.id}">
            //                                                                     <i class="ri-close-line align-bottom me-2 text-muted"></i>
            //                                                                     Rejeter
            //                                                                 </a>
            //                                                             </li>
            //                                                         ` : ''}
            //                             </ul>
            //                         </div>
            //                     </td>
            //                 </tr>
            //             `);

            //         lastIds.push(item.id);
            //     }
            // });

            // // Réindexe les lignes après ajout
            // $('#buttons-datatables tbody tr').each(function(index) {
            //     $(this).find('td:first').text(index + 1);
            // });

            if (newItems.length > 0) {
                const alertContainer = document.getElementById(
                    "alert-checkOffertNotification"
                );

                // URL vers offert.index depuis Blade (à adapter si dans .js externe)
                const offertIndexUrl = "/admin/offert";

                // Création de l'alerte personnalisée
                const alertDiv = document.createElement("div");
                alertDiv.className =
                    "alert alert-success alert-dismissible alert-label-icon label-arrow fade show material-shadow";
                alertDiv.setAttribute("role", "alert");

                alertDiv.innerHTML = `
                    <i class="ri-notification-off-line label-icon"></i>
                    <strong>Notification : Vous avez ${newItems.length} offerts non approuvés! veuillez les consulter et les approuver pour que la vente soit validée</strong> .
                    <a href="${offertIndexUrl}" class="btn btn-sm btn-success ms-2">Voir les offerts</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                alertContainer.appendChild(alertDiv);

                // 🔊 Joue le son
                const alertSound = new Audio("/sounds/sound1.mp3");
                alertSound.play();

                // Auto-fermeture après 5 secondes
                setTimeout(() => {
                    const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
                    alert.close();
                }, 5000);
            }
        },
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error(
            "Erreur lors de la récupération des offerts:",
            textStatus,
            errorThrown
        );
    });
}

// Vérifie toutes les 10 secondes
setInterval(checkOfferts, 10000);
