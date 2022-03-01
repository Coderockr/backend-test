let valueParser = require('postcss-value-parser')

function parseValue (value) {
  let parsed = value.match(/([\d.-]+)(.*)/)
  if (!parsed || !parsed[1] || !parsed[2] || isNaN(parsed[1])) {
    return undefined
  }
  return [parseFloat(parsed[1]), parsed[2]]
}

function compose (first, second, third) {
  if (first && second && third) {
    return `max(${first}, min(${second}, ${third}))`
  }
  if (first && second) {
    return `max(${first}, ${second})`
  }

  return first
}

function updateValue (declaration, value, preserve) {
  let newValue = value
  let newValueAst = valueParser(value)
  let valueAST = valueParser(declaration.value)

  // Means clamp is not alone within the declaration
  if (valueAST.nodes.length > 1) {
    let clampIndex = valueAST.nodes.findIndex(
      node => node.type === 'function' && node.value === 'clamp'
    )

    valueAST.nodes = [
      ...valueAST.nodes.slice(0, clampIndex),
      ...newValueAst.nodes,
      ...valueAST.nodes.slice(clampIndex + 1)
    ]

    newValue = valueAST.toString()
  }

  if (preserve) {
    declaration.cloneBefore({ value: newValue })
  } else {
    declaration.value = newValue
  }
}

module.exports = opts => {
  opts = opts || {}
  let precalculate = opts.precalculate ? Boolean(opts.precalculate) : false
  let preserve = opts.preserve ? Boolean(opts.preserve) : false

  return {
    postcssPlugin: 'postcss-clamp',
    Declaration (decl) {
      if (!decl || !decl.value.includes('clamp')) {
        return
      }
      valueParser(decl.value).walk(node => {
        let nodes = node.nodes
        if (
          node.type !== 'function' ||
          node.value !== 'clamp' ||
          nodes.length !== 5
        ) {
          return
        }
        let first = nodes[0]
        let second = nodes[2]
        let third = nodes[4]
        let naive = compose(
          valueParser.stringify(first),
          valueParser.stringify(second),
          valueParser.stringify(third)
        )
        if (!precalculate || second.type !== 'word' || third.type !== 'word') {
          updateValue(decl, naive, preserve)
          return
        }
        let parsedSecond = parseValue(second.value)
        let parsedThird = parseValue(third.value)
        if (parsedSecond === undefined || parsedThird === undefined) {
          updateValue(decl, naive, preserve)
          return
        }
        let [secondValue, secondUnit] = parsedSecond
        let [thirdValue, thirdUnit] = parsedThird
        if (secondUnit !== thirdUnit) {
          updateValue(decl, naive, preserve)
          return
        }
        let parsedFirst = parseValue(first.value)
        if (parsedFirst === undefined) {
          let secondThirdValue = `${secondValue + thirdValue}${secondUnit}`
          updateValue(
            decl,
            compose(valueParser.stringify(first), secondThirdValue),
            preserve
          )
          return
        }
        let [firstValue, firstUnit] = parsedFirst
        if (firstUnit !== secondUnit) {
          let secondThirdValue = `${secondValue + thirdValue}${secondUnit}`
          updateValue(
            decl,
            compose(valueParser.stringify(first), secondThirdValue),
            preserve
          )
          return
        }

        updateValue(
          decl,
          compose(`${firstValue + secondValue + thirdValue}${secondUnit}`),
          preserve
        )
      })
    }
  }
}

module.exports.postcss = true
