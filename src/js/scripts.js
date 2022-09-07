const array1 = ['shop_manager']
const array2 = ['customer', 'administrator']

const findInArrays = (array1, array2) => {
    let isExist = false

    array1.map(element => {
        if (array2.includes(element)) {
            isExist = true
        }
    })

    if (!isExist) {
        array2.map(element => {
            if (array1.includes(element)) {
                isExist = true
            }
        })
    }

    return isExist;
}

console.log(findInArrays(array1, array2))