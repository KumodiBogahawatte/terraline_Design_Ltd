class Lightbox {
    constructor() {
        this.currentIndex = 0;
        this.images = [];
        this.lightbox = null;
        this.init();
    }

    init() {
        this.createLightbox();

        document.querySelectorAll('[data-lightbox]').forEach((el, index) => {
            el.addEventListener('click', (e) => {
                e.preventDefault();
                this.open(index);
            });

            // Support <a href="..."> or <img src="...">
            const src     = el.tagName === 'A' ? el.getAttribute('href') : el.src;
            const imgEl   = el.tagName === 'A' ? el.querySelector('img') : el;
            this.images.push({
                src:     src,
                caption: el.getAttribute('data-caption') || '',
                alt:     imgEl ? imgEl.alt : ''
            });
        });
    }

    createLightbox() {
        const lb = document.createElement('div');
        lb.className = 'lightbox-gallery';
        lb.id = 'lightbox';
        lb.innerHTML = `
            <span class="lightbox-close">&times;</span>
            <span class="lightbox-prev">&#8592;</span>
            <span class="lightbox-next">&#8594;</span>
            <div class="lightbox-content">
                <img src="" alt="">
                <div class="lightbox-caption"></div>
            </div>
            <div class="lightbox-counter"></div>
        `;
        document.body.appendChild(lb);

        this.lightbox      = lb;
        this.imgEl         = lb.querySelector('.lightbox-content img');
        this.captionEl     = lb.querySelector('.lightbox-caption');
        this.counterEl     = lb.querySelector('.lightbox-counter');

        lb.querySelector('.lightbox-close').addEventListener('click', () => this.close());
        lb.querySelector('.lightbox-prev').addEventListener('click',  () => this.navigate(-1));
        lb.querySelector('.lightbox-next').addEventListener('click',  () => this.navigate(1));

        // Click backdrop (not content) to close
        lb.addEventListener('click', (e) => {
            if (e.target === lb) this.close();
        });

        // Keyboard
        document.addEventListener('keydown', (e) => {
            if (!this.lightbox.classList.contains('active')) return;
            if (e.key === 'Escape')      this.close();
            if (e.key === 'ArrowLeft')   this.navigate(-1);
            if (e.key === 'ArrowRight')  this.navigate(1);
        });

        // Touch swipe
        let touchStartX = null;
        lb.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
        }, { passive: true });
        lb.addEventListener('touchend', (e) => {
            if (touchStartX === null) return;
            const dx = e.changedTouches[0].clientX - touchStartX;
            if (Math.abs(dx) > 40) this.navigate(dx < 0 ? 1 : -1);
            touchStartX = null;
        });
    }

    open(index) {
        this.currentIndex = index;
        this.updateImage();
        this.lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    close() {
        this.lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    navigate(direction) {
        this.currentIndex = (this.currentIndex + direction + this.images.length) % this.images.length;
        this.updateImage();
    }

    updateImage() {
        const item = this.images[this.currentIndex];
        // Fade out, swap, fade in
        this.imgEl.classList.add('lb-loading');
        const tmp = new Image();
        tmp.onload = () => {
            this.imgEl.src = tmp.src;
            this.imgEl.alt = item.alt;
            this.imgEl.classList.remove('lb-loading');
        };
        tmp.src = item.src;
        this.captionEl.textContent = item.caption;
        this.counterEl.textContent = `${this.currentIndex + 1} / ${this.images.length}`;
    }
}

document.addEventListener('DOMContentLoaded', () => { new Lightbox(); });