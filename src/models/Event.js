const { Model, DataTypes } = require('sequelize')
const sequelizePaginate = require('sequelize-paginate')

class Events extends Model {


    static init(sequelize) {
        super.init({
            Name: {
                type: DataTypes.STRING,
                allowNull: false
            },
            Description: {
                type: DataTypes.STRING,
                allowNull: false
            },
            Region: {
                type: DataTypes.STRING,
                allowNull: false
            },
            Date: {
                type: DataTypes.DATE,
                allowNull: false
            }
        }, {
            sequelize
        })
        sequelizePaginate.paginate(Events)
        return Events
    }
}

module.exports = Events