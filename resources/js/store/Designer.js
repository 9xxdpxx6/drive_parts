export default {
    isColorDark(color) {
        const rgb = this.getRGB(color)
        const brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000
        return brightness < 128
    },

    getRGB(color) {
        const canvas = document.createElement('canvas')
        const ctx = canvas.getContext('2d')
        ctx.fillStyle = color
        ctx.fillRect(0, 0, 1, 1)
        const [r, g, b] = ctx.getImageData(0, 0, 1, 1).data
        return { r, g, b }
    },

    formatPhone(phone) {
        const cleaned = ('' + phone).replace(/\D/g, '')
        const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/)
        if (match) {
            return '+7 (' + match[1] + ') ' + match[2] + '-' + match[3]
        }
        return null
    },

    maskPhone(phone) {
        let cleaned = phone.replace(/\D/g, '')
        if (cleaned.length === 0) {
            phone = ''
        } else if (cleaned.length <= 3) {
            phone = '(' + cleaned
        } else if (cleaned.length <= 6) {
            phone = '(' + cleaned.slice(0, 3) + ') ' + cleaned.slice(3)
        } else {
            phone = '(' + cleaned.slice(0, 3) + ') ' + cleaned.slice(3, 6) + '-' + cleaned.slice(6, 10)
        }
        return phone
    },

    formatDateTime(date) {
        date = new Date(date)
        const formattedDate = date.toLocaleDateString('ru', { day: '2-digit', month: '2-digit', year: 'numeric' })
        const formattedTime = date.toLocaleTimeString('ru', { hour: '2-digit', minute: '2-digit' })
        return `${formattedDate} | ${formattedTime}`
    },

    formatPrice(price, qty = 1) {
        return Number(price * qty).toFixed(2)
    }
}
