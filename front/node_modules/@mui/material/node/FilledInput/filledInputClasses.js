"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;
exports.getFilledInputUtilityClass = getFilledInputUtilityClass;

var _base = require("@mui/base");

function getFilledInputUtilityClass(slot) {
  return (0, _base.generateUtilityClass)('MuiFilledInput', slot);
}

const filledInputClasses = (0, _base.generateUtilityClasses)('MuiFilledInput', ['root', 'colorSecondary', 'underline', 'focused', 'disabled', 'adornedStart', 'adornedEnd', 'error', 'sizeSmall', 'multiline', 'hiddenLabel', 'input', 'inputSizeSmall', 'inputHiddenLabel', 'inputMultiline', 'inputAdornedStart', 'inputAdornedEnd']);
var _default = filledInputClasses;
exports.default = _default;