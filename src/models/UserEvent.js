const { Model, DataTypes } = require('sequelize')
const sequelizePaginate = require('sequelize-paginate')
const User = require('./User')
const Events = require('./Event')

class UserEvent extends Model {
    static init(sequelize) {
        super.init({
            user_id: {
                type: DataTypes.INTEGER,
                references: {
                    model: User,
                    key: 'id'
                }
            },
            event_id: {
                type: DataTypes.INTEGER,
                references: {
                    model: Events,
                    key: 'id'
                }
            }
        }, {
            sequelize
        })
        sequelizePaginate.paginate(UserEvent)
        return UserEvent
    }
}

module.exports = UserEvent