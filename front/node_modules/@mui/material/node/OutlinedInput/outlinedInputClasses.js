"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;
exports.getOutlinedInputUtilityClass = getOutlinedInputUtilityClass;

var _base = require("@mui/base");

function getOutlinedInputUtilityClass(slot) {
  return (0, _base.generateUtilityClass)('MuiOutlinedInput', slot);
}

const outlinedInputClasses = (0, _base.generateUtilityClasses)('MuiOutlinedInput', ['root', 'colorSecondary', 'focused', 'disabled', 'adornedStart', 'adornedEnd', 'error', 'sizeSmall', 'multiline', 'notchedOutline', 'input', 'inputSizeSmall', 'inputMultiline', 'inputAdornedStart', 'inputAdornedEnd']);
var _default = outlinedInputClasses;
exports.default = _default;