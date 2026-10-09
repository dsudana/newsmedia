# Modern Card Animation Guide

## Overview
Kami telah menambahkan animasi modern dan smooth pada semua card components di frontend. Animasi dirancang untuk:
- Meningkatkan pengalaman pengguna (UX)
- Memberikan feedback visual yang jelas
- Tetap sophisticated dan tidak berlebihan
- Menghormati preferensi reduced-motion

## Animation Classes

### 1. `.card-lift`
**Efek:** Card terangkat ke atas saat hover
```css
- Transform: translateY(-8px)
- Shadow: meningkat menjadi 0 20px 25px
- Duration: 300ms
- Easing: cubic-bezier(0.34, 1.56, 0.64, 1) [bounce easing]
```
**Digunakan pada:** Featured articles, event cards, category cards, video cards

### 2. `.image-zoom-container` + `.image-zoom`
**Efek:** Gambar zoom smooth dengan brightness fade
```css
- Transform: scale(1.08)
- Filter: brightness(0.92)
- Duration: 350ms
- Easing: cubic-bezier(0.34, 1.56, 0.64, 1)
```
**Digunakan pada:** Semua image dalam card

### 3. `.stagger-item`
**Efek:** Fade-in dan slide-up secara bertahap untuk grid items
```css
- Opacity: 0 → 1
- Transform: translateY(20px) → translateY(0)
- Duration: 600ms
- Delay: 0ms, 100ms, 200ms, 300ms, 400ms, 500ms (bergantung urutan)
```
**Digunakan pada:** Article grids, video grids, event grids

### 4. `.icon-rotate`
**Efek:** Icon berputar 360° saat hover
```css
- Transform: rotate(0deg) → rotate(360deg)
- Duration: 300ms
```
**Digunakan pada:** Category badges/icons

### 5. `.badge-pulse`
**Efek:** Badge berdenyut (pulse animation)
```css
- Box-shadow: meningkat radius
- Animation: 2s infinite
```
**Untuk masa depan:** Bisa digunakan untuk status badges

### 6. `.shadow-depth`
**Efek:** Shadow elevation pada hover
```css
- Box-shadow: 0 4px 6px → 0 25px 50px
- Duration: 300ms
```
**Untuk masa depan:** Bisa digunakan untuk card alternatif

## Pages dengan Animasi

### 1. Gallery (`/gallery`)
- Video card grid dengan card-lift dan image-zoom
- Stagger animation pada video items

### 2. Blog Index (`/blog`)
- Featured article dengan card-lift dan image-zoom
- Article grid dengan stagger-item animation

### 3. Events (`/acara`)
- Event card grid dengan card-lift dan image-zoom
- Stagger animation pada event items

### 4. Categories (`/kategori`)
- Category card dengan card-lift dan icon-rotate
- Stagger animation pada category items

### 5. Home (`/`)
- Featured article card dengan card-lift dan image-zoom
- Article grid sections dengan stagger animation
- Update Berita section dengan stagger animation

## Technical Details

### Easing Functions
- **Bounce Effect**: `cubic-bezier(0.34, 1.56, 0.64, 1)` - untuk lift effect
- **Ease**: Untuk shadow dan opacity changes

### Timing
- Card elevation: 300ms
- Image zoom: 350ms
- Stagger entrance: 600ms dengan delays
- Icon rotate: 300ms

### Dark Mode Support
Semua animasi memiliki dukungan dark mode:
- Box shadows disesuaikan untuk dark backgrounds
- Color transitions bekerja dengan dark color scheme

### Accessibility
- **Reduced Motion**: Semua animasi dihormati dengan `prefers-reduced-motion`
- **Focus States**: Tab navigation tetap smooth
- **Touch Targets**: Min 44x44px

## CSS Structure

Semua animasi didefinisikan dalam `/resources/css/custom.css`:
- Lines 356-521: Definisi animasi dan keyframes
- Lines 522-547: Reduced motion overrides

## Performance Notes

- GPU-accelerated: transform dan opacity
- No layout thrashing
- Smooth 60fps pada modern devices
- Minimal repaints dengan selective property animations

## Future Enhancements

1. Scroll-triggered animations dengan Intersection Observer
2. Stagger animations untuk sidebar items
3. More sophisticated gesture responses (3D tilt, etc.)
4. Custom animation control via data attributes
5. Animation presets untuk different card types

## Testing Checklist

- [x] Card lift on hover
- [x] Image zoom with brightness
- [x] Stagger grid animations
- [x] Dark mode support
- [x] Reduced motion preference
- [x] Smooth transitions (no jank)
- [ ] Browser compatibility (Chrome, Firefox, Safari, Edge)
- [ ] Mobile touch responsiveness
- [ ] Performance metrics (Core Web Vitals)
