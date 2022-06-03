import accounting from "accounting"
export const CustomRender = {
	name: "custom-render",
	functional: true,
	props: {
		row: Object,
		render: Function,
		index: Number,
		column: {
			type: Object,
			default: null
		}
	},
	render: (h, ctx) => {
		const params = {
			row: ctx.props.row,
			index: ctx.props.index
		}
		if (ctx.props.column) params.column = ctx.props.column
		return ctx.props.render(h, params)
	}
}

export const editorToolbar = [
	["bold", "italic", "underline", "strike"],
	[{ list: "ordered" }, { list: "bullet" }, { list: "check" }],
	["blockquote", "code-block", "link", "image"],
	[
		{ align: "" },
		{ align: "center" },
		{ align: "right" },
		{ align: "justify" }
	],
	[{ header: 1 }, { header: 2 }],
	[{ color: [] }, { background: [] }],
	["clean"]
]

export const editorToolbarMin = [
	["bold", "italic", "underline"],
	[{ list: "ordered" }, { list: "bullet" }],
	[
		{ align: "" },
		{ align: "center" },
		{ align: "right" },
		{ align: "justify" }
	],
	[{ color: [] }, { background: [] }],
	["clean"]
]

export const queryCep = async (cep) => {
	const cepNum = cep ? cep.match(/\d+/gi).join("") : {}
	if (cepNum.length !== 8) return false
	try {
		const response = await fetch(`//viacep.com.br/ws/${cepNum}/json/`)
		const data = await response.json()
		if (data.erro) return false
		return data
	} catch (err) {
		return false
	}
}

const moment_ = require('moment')
require('moment/locale/pt-br')
moment_.locale('pt-br')

export const moment = moment_

export const view = {
    insertBody(elx, parent){
      let bodyx = parent ? parent : document.body
      bodyx.insertBefore(elx, bodyx.firstChild)
    },
    removeBody(element, parent) {
      let bodyx = parent ? parent : document.body
      bodyx.removeChild(element);
    },
    changePosition(elx,content,conditional){
      let topx = 0
      let leftx = 0
      let widthx = 0
      let scrollTopx = window.pageYOffset || document.documentElement.scrollTop;
      if(elx.getBoundingClientRect().top + 300 >= window.innerHeight) {
        setTimeout( ()=> {
          if(conditional){
            topx = (elx.getBoundingClientRect().top - content.clientHeight) + scrollTopx
          } else {
            topx = (elx.getBoundingClientRect().top - content.clientHeight + elx.clientHeight) + scrollTopx
          }
        }, 1);
  
      } else {
        topx = conditional?(elx.getBoundingClientRect().top + elx.clientHeight) + scrollTopx + 5:elx.getBoundingClientRect().top + scrollTopx
      }
  
      leftx = elx.getBoundingClientRect().left
      widthx = elx.offsetWidth
  
      let cords = {
        left: `${leftx}px`,
        top: `${topx}px`,
        width: `${widthx}px`
      }
  
      return cords
    },
}

export const toDecimal = (value) => {
	if(value){
		value = value.replace(/[.\s]+|[.\s]+/g, '')
	}
	if(value.includes(',')){
		value = value.replace(',','.')
	}
	return parseFloat(value)
}

export const formatterCoin = (value) => {
	if(!value){
		value = '0,00'
	}else{
		if(typeof value  === 'number'){
			value = accounting.formatNumber(value, 2, ".", ",")
		}
	}
	return value
}

export const maskCpfCnpj = (value) => {
	if(value){
		value = value.replace(/\D/g,'')
		return value.length <= 11 ? '###.###.###-##' : '##.###.###/####-##'
	}else{
		return '###.###.###-##'
	}
}

export const validateCpfCnpj = (value) => {
	let span = ''
	if(value){
		value = value.replace(/\D/g,'')
		span+= value.length <= 11 ? 'cpf' : 'cnpj'
	}else{
		span+= 'cpf'
	}
	return span
}