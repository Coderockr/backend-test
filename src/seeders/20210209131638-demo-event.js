'use strict';

const data = [{
        Name: "Hackaton Provi",
        Description: "hackaton da Provi para aumentar suas softskills e hardskills, Venha fazer muito networking",
        Date: "2021-02-08T18:48:25.042Z"
    },

]
module.exports = {
    up: async(queryInterface, Sequelize) => {
        await queryInterface.bullInsert('Events', data);
    },

    down: async(queryInterface, Sequelize) => {
        await queryInterface.bulkDelete('Events', null, {});
    }
};