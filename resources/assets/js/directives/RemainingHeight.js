let elems = [];
const resizeElems = () => {
    elems.forEach(elem => resize(elem))
}

const resize = (elem) => {
    const rect = elem.getBoundingClientRect();
    elem.style.height = (window.innerHeight - rect.top) + "px"
}

export default {
    inserted(el) {
        elems.push(el);
        window.addEventListener('resize', resizeElems);
        resize(el);
    },
    
    unbind(el) {
        elems = elems.filter(e => e != el);
        if (elems.length === 0) {
            window.removeEventListener('resize', resizeElems);
        }
    },
}		
