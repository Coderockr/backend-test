module.exports = mongoose => {
    var schema = mongoose.Schema(
      {
       owner_name:String
      },
      { timestamps: true }
    );
    schema.method("toJSON", function() {
      const { __v, _id, ...object } = this.toObject();
      object.id = _id;
      return object;
    });
    const Owner = mongoose.model("owner", schema);
    return Owner;
  };