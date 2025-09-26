import { createApp } from "vue";
console.log("Vue loaded !");

createApp({
    data() {
        return {
            sites: [{ name:'', departments: [] }]
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
        }
    }
}).mount('#qr-form');

