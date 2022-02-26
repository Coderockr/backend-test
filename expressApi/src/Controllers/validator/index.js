function validator(type, value) {
    const reString = /^([a-zA-ZÀ-ÿ\u00f1\u00d1]*)+$/;
    const reEmail = /^((?!\.)[\w_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;
    const reNumber = /^[0-9]+$/;
    const reAny = /^[\s\S]{1,}$/;
    const reFloat = /(([-+])?[.,]\b(\d+)(?:[Ee]([+-])?(\d+)?)?\b)|(?:([+-])?\b(\d+)(?:[.,]?(\d+))?(?:[Ee]([+-])?(\d+)?)?\b)/gm;

    if (value == undefined) {
        return false;
    }

    switch (type) {
        case "any":
            return reAny.test(value);
        case "number":
            return reNumber.test(value);
        case "float":
            return reFloat.test(value);
        case "email":
            return reEmail.test(value);
        case "text":
            return reString.test(value);
    }
};

module.exports = { validator };