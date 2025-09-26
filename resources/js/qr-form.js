import { createApp } from "vue";
console.log("Vue loaded !");

createApp({
    data() {
        return {
            sites: [{ name:'', departments: [] }],
            qrImage: null
        }
    },
    methods: {
        addSite() {
            this.sites.push({name:'', departments: []});
        },
        addDepartment(sIndex) {
            this.sites[sIndex].departments.push({name:'', poles: []});
        },
        addPole(sIndex, dIndex) {
            this.sites[sIndex].departments[dIndex].poles.push({name:'', equipments: []});
        },
        addEquipment(sIndex, dIndex, pIndex) {
            this.sites[sIndex].departments[dIndex].poles[pIndex].equipments.push({name:''});
        },
        submitForm() {
            fetch("/generate-qr", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ data: this.sites })
            })
            .then(res => res.blob())
            .then(blob => {
                // ⚡ Téléchargement automatique du PDF
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = "qr_code.png";
                a.click();
                URL.revokeObjectURL(url);

                // ⚡ Optionnel : afficher l’aperçu sur la page
                this.qrImage = url;
            });
        }
    }
}).mount('#qr-form'); // ⚡ très important

