const { Model, DataTypes } = require('sequelize')
const sequelizePaginate = require('sequelize-paginate')

class User extends Model {

    static associate(models) {
        User.belongsToMany(models.Event, { through: 'UserEvent', as: 'events', foreignKey: 'user_id' })
    }

    static init(sequelize) {
        super.init({
            Name: {
                type: DataTypes.STRING,
                allowNull: false
            },
            Email: {
                type: DataTypes.STRING,
                allowNull: false

            },
            Password: {
                type: DataTypes.STRING,
                allowNull: false
            },
            Bio: {
                type: DataTypes.STRING,
                allowNull: true

            },
            Profile_Picture: {
                type: DataTypes.STRING,
                allowNull: true

            },
            City: {
                type: DataTypes.STRING,
                allowNull: true

            },
            State: {
                type: DataTypes.STRING,
                allowNull: true

            },

        }, {
            sequelize
        })
        sequelizePaginate.paginate(User)
        return User

    }
}

module.exports = User