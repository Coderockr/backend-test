"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;
exports.getInputUtilityClass = getInputUtilityClass;

var _base = require("@mui/base");

function getInputUtilityClass(slot) {
  return (0, _base.generateUtilityClass)('MuiInput', slot);
}

const inputClasses = (0, _base.generateUtilityClasses)('MuiInput', ['root', 'formControl', 'focused', 'disabled', 'colorSecondary', 'underline', 'error', 'sizeSmall', 'multiline', 'fullWidth', 'input', 'inputSizeSmall', 'inputMultiline', 'inputTypeSearch']);
var _default = inputClasses;
exports.default = _default;