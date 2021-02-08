'use strict';
const {
    Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
    class UserEvent extends Model {

        static associate(models) {
            // define association here
        }
    };
    UserEvent.init({
        UserId: {
            type: DataTypes.INTEGER,
            references: {
                model: User,
                key: 'id'
            }
        },
        EventId: {
            type: DataTypes.INTEGER,
            references: {
                model: Events,
                key: 'id'
            }
        }
    }, {
        sequelize,
        modelName: 'UserEvent',
    });
    return UserEvent;
};