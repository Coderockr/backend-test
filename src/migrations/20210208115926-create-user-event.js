'use strict';
module.exports = {
    up: async(queryInterface, Sequelize) => {
        await queryInterface.createTable('UserEvents', {
            id: {
                allowNull: false,
                autoIncrement: true,
                primaryKey: true,
                type: Sequelize.INTEGER
            },
            user_id: {
                type: Sequelize.INTEGER,
                references: { model: 'Users', key: 'id' },
                onUpdate: 'CASCADE',
                onDelete: 'CASCADE'
            },
            event_id: {
                type: Sequelize.INTEGER,
                references: { model: 'Events', key: 'id' },
                onUpdate: 'CASCADE',
                onDelete: 'CASCADE'

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
        await queryInterface.dropTable('UserEvents');
    }
};