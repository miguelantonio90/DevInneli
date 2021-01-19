export const randomElement = (arr = []) => {
  return arr[Math.floor(Math.random() * arr.length)]
}

export const kebab = (str) => {
  return (str || '').replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase()
}

export const compareValues = (key, order = 'asc') => {
  return function innerSort (a, b) {
    // eslint-disable-next-line no-prototype-builtins
    if (!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
      return 0
    }

    const varA = (typeof a[key] === 'string')
      ? a[key].toUpperCase() : a[key]
    const varB = (typeof b[key] === 'string')
      ? b[key].toUpperCase() : b[key]

    let comparison = 0
    if (varA > varB) {
      comparison = 1
    } else if (varA < varB) {
      comparison = -1
    }
    return (
      (order === 'desc') ? (comparison * -1) : comparison
    )
  }
}

export const serialMaker = () => {
  let prefix = ''
  let seq = 0
  return {
    set_prefix: function (p) {
      prefix = String(p)
    },
    set_seq: function (s) {
      seq = s
    },
    gensym: function () {
      const result = prefix + seq
      seq += 1
      return result
    }
  }
}
export const fullName = (firstName, lastName) => {
  firstName = firstName != null ? firstName : ''
  lastName = lastName != null ? lastName : ''

  return firstName + ' ' + lastName
}

export const toggleFullScreen = () => {
  const doc = window.document
  const docEl = doc.documentElement

  const requestFullScreen =
    docEl.requestFullscreen ||
    docEl.mozRequestFullScreen ||
    docEl.webkitRequestFullScreen ||
    docEl.msRequestFullscreen
  const cancelFullScreen =
    doc.exitFullscreen ||
    doc.mozCancelFullScreen ||
    doc.webkitExitFullscreen ||
    doc.msExitFullscreen

  if (
    !doc.fullscreenElement &&
    !doc.mozFullScreenElement &&
    !doc.webkitFullscreenElement &&
    !doc.msFullscreenElement
  ) {
    requestFullScreen.call(docEl)
  } else {
    cancelFullScreen.call(doc)
  }
}

export default {
  randomElement,
  toggleFullScreen,
  kebab,
  compareValues,
  serialMaker,
  fullName
}
