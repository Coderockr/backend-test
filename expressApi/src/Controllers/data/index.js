class Data {
    static investments = [];
    static owners = [];

    updateInvestments() {
        Data.investments.map((item, i) => {
            Data.investments[i].update()
        });
    };
};


module.exports = { Data };
/**It calls for a database */