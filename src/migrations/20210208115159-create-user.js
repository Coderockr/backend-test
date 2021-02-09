'use strict';
module.exports = {
    up: async(queryInterface, Sequelize) => {
        await queryInterface.createTable('Users', {
            id: {
                allowNull: false,
                autoIncrement: true,
                primaryKey: true,
                type: Sequelize.INTEGER
            },
            Name: {
                type: Sequelize.STRING
            },
            Email: {
                type: Sequelize.STRING
            },
            Password: {
                type: Sequelize.STRING
            },
            Bio: {
                type: Sequelize.STRING
            },
            Profile_Picture: {
                type: Sequelize.STRING,
                allowNull: true

            },
            City: {
                type: Sequelize.STRING,
                allowNull: true

            },
            State: {
                type: Sequelize.STRING,
                allowNull: true

            },

            createdAt: {
                allowNull: false,
                type: Sequelize.DATE
            },
            updatedAt: {
                allowNull: false,
                type: Sequelize.DATE
            }
        });
    },
    down: async(queryInterface, Sequelize) => {
        await queryInterface.dropTable('Users');
    }
};