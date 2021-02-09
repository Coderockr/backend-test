'use strict';

module.exports = {
    up: async(queryInterface, Sequelize) => {

    },

    down: async(queryInterface, Sequelize) => {
        await queryInterface.bulkDelete('UserEvents', null, {});
    }
};