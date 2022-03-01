module.exports = mongoose => {
    var schema = mongoose.Schema(
      {
        owner: String,
        valueInitalInvest: Number,
        expectedBalance: Number,
        dateInvest: Date,
        withdrawn:Boolean,
        valueWithdrawn:Number,
        dateWithdrawn:Date
      }
    );
    schema.method("toJSON", function() {
      const { __v, _id, ...object } = this.toObject();
      object.id = _id;
      return object;
    });
    const Invest = mongoose.model("invest", schema);
    return Invest;
  };