import "./bootstrap";
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import Swal from "sweetalert2";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

window.Echo.channel("offerts").listen(".offert.approved", (e) => {
    console.log("Un offert a été approuvé !", e.offert);
    Swal.fire({
        title: "Nouveau offert approuvé",
        text: `Offert #${e.offert.id} approuvé`,
        icon: "info",
    });
});


