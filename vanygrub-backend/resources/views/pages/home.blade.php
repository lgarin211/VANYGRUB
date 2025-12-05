@extends('layouts.app')

@section('title', 'VANY GROUP - Exclusive Batak Ethnic Collection')

@section('styles')
@import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Poppins", sans-serif;
  background: #fafafa url('https://asset.kompas.com/crops/eV3pVhsTUUlB_nffNUO84gOd4UQ=/34x11:951x622/750x500/data/photo/2022/02/28/621c6c100fc46.jpg') repeat;
  background-size: 300px 200px;
  opacity: 1;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  user-select: none;
  padding: 20px 15px;
  position: relative;
  overflow-x: hidden;
  min-height: 100dvh;
}

body::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(250, 250, 250, 0.7);
  z-index: 0;
  pointer-events: none;
}

.header {
  text-align: center;
  margin-bottom: 40px;
  margin-top: 20px;
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 100%;
}

.subtitle {
  color: maroon;
  font-size: 14px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 16px;
}

.main-title {
  font-size: clamp(48px, 6vw, 72px);
  font-weight: 700;
  letter-spacing: 6px;
  color: #0a0a0a;
  line-height: 1.2;
  font-family: 'Playfair Display', serif;
  text-transform: uppercase;
  text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
  margin: 20px 0 40px 0;
  background: linear-gradient(135deg, #800020, #9d0032, #b30045, #DAA520);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-style: italic;
}

.slider-container {
  perspective: 1500px;
  perspective-origin: 50% 50%;
  cursor: grab;
  width: 100%;
  max-width: 1400px;
  overflow: hidden;
  position: relative;
  z-index: 1;
}

.slider-container.dragging {
  cursor: grabbing;
}

.slider-track {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transform-style: preserve-3d;
}

.card {
  flex-shrink: 0;
  width: 240px;
  background: white;
  overflow: hidden;
  transform-style: preserve-3d;
  position: relative;
  cursor: pointer;
}

.card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    to right,
    rgba(0, 0, 0, 0.15),
    transparent 30%,
    transparent 70%,
    rgba(0, 0, 0, 0.15)
  );
  transform: translateZ(-8px);
  pointer-events: none;
}

.card::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #e0e0e0;
  transform: translateZ(-16px);
  box-shadow: 0 0 40px rgba(0, 0, 0, 0.3);
  pointer-events: none;
}

.card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  pointer-events: none;
  position: relative;
  z-index: 1;
}

.card .hover-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: 2;
}

.card:hover .hover-overlay {
  opacity: 1;
}

.card .hover-overlay span {
  color: white;
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.slider-track.blurred .card:not(.expanded) {
  filter: blur(8px);
  transition: filter 0.6s ease;
}

.card.expanded {
  z-index: 1000 !important;
}

.card-info {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  opacity: 0;
  pointer-events: none;
  transition: all 0.4s ease;
  z-index: 1001;
  max-width: 600px;
  padding: 1.5rem;
  background: #ff6b35;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  min-width: 375px;
  border-radius: 15px;
  border: 2px solid rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}

.card-info.visible {
  opacity: 1;
  pointer-events: all;
}

.card-info h2 {
  font-size: 36px;
  font-weight: 900;
  color: #0a0a0a;
  margin-bottom: 16px;
}

.card-info p {
  font-size: 18px;
  color: #080808;
  line-height: 1.7;
}

.close-btn {
  position: fixed;
  top: 40px;
  right: 40px;
  width: 60px;
  height: 60px;
  background: rgba(255, 255, 255, 0.95);
  border: 2px solid rgba(255, 107, 53, 0.3);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 1002;
  opacity: 0;
  pointer-events: none;
  transition: all 0.3s ease;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
  -webkit-tap-highlight-color: transparent;
  touch-action: manipulation;
}

.close-btn.visible {
  opacity: 1;
  pointer-events: all;
}

.close-btn:hover {
  background: #ff6b35;
  color: white;
  transform: rotate(90deg) scale(1.1);
}

.close-btn svg {
  width: 24px;
  height: 24px;
}

/* Modal Popup Styles for Gallery2 */
.gallery2-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.95);
  backdrop-filter: blur(15px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  animation: gallery2ModalFadeIn 0.3s ease-out;
}

@keyframes gallery2ModalFadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.gallery2-modal-content {
  position: relative;
  max-width: 90vw;
  max-height: 90vh;
  display: flex;
  background: linear-gradient(135deg, rgba(128, 0, 0, 0.15), rgba(0, 0, 0, 0.1));
  backdrop-filter: blur(25px);
  border-radius: 25px;
  border: 1px solid rgba(255, 255, 255, 0.15);
  overflow: hidden;
  animation: gallery2ModalSlideIn 0.4s ease-out;
  box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
}

@keyframes gallery2ModalSlideIn {
  from {
    transform: translateY(60px) scale(0.9);
    opacity: 0;
  }
  to {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
}

.gallery2-modal-close {
  position: absolute;
  top: 25px;
  right: 25px;
  z-index: 2001;
  background: rgba(128, 0, 0, 0.2);
  backdrop-filter: blur(15px);
  border: none;
  border-radius: 50%;
  width: 55px;
  height: 55px;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.gallery2-modal-close:hover {
  background: rgba(128, 0, 0, 0.4);
  transform: scale(1.1) rotate(180deg);
  box-shadow: 0 10px 20px rgba(128, 0, 0, 0.3);
}

.gallery2-modal-image-container {
  position: relative;
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 500px;
  background: rgba(0, 0, 0, 0.1);
}

.gallery2-modal-image {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 50px;
}

.gallery2-modal-image img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  border-radius: 20px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
  transition: transform 0.3s ease;
}

.gallery2-modal-image img:hover {
  transform: scale(1.02);
}

.gallery2-modal-nav {
  position: absolute !important;
  top: 50% !important;
  transform: translateY(-50%) !important;
  background: rgba(0, 0, 0, 0.8) !important;
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255, 255, 255, 0.4) !important;
  border-radius: 50% !important;
  width: 70px !important;
  height: 70px !important;
  color: white !important;
  cursor: pointer;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  transition: all 0.3s ease;
  z-index: 2001 !important;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
  font-size: 20px !important;
  font-weight: bold !important;
  visibility: visible !important;
  opacity: 1 !important;
}

.gallery2-modal-nav:hover {
  background: rgba(128, 0, 0, 0.9);
  transform: translateY(-50%) scale(1.1);
  box-shadow: 0 8px 25px rgba(128, 0, 0, 0.6);
  border-color: rgba(255, 255, 255, 0.6);
}

.gallery2-modal-prev {
  left: 25px !important;
  display: flex !important;
  visibility: visible !important;
  opacity: 1 !important;
}

.gallery2-modal-next {
  right: 25px !important;
  display: flex !important;
  visibility: visible !important;
  opacity: 1 !important;
}

.gallery2-modal-info {
  flex: 0 0 420px;
  padding: 50px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
  backdrop-filter: blur(10px);
}

.gallery2-modal-category {
  color: #800000;
  font-size: 15px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 3px;
  margin-bottom: 15px;
  padding: 8px 16px;
  background: rgba(128, 0, 0, 0.1);
  border-radius: 25px;
  align-self: flex-start;
  border: 1px solid rgba(128, 0, 0, 0.2);
}

.gallery2-modal-title {
  color: white;
  font-size: 42px;
  font-weight: 900;
  margin-bottom: 25px;
  line-height: 1.1;
  text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
}

.gallery2-modal-description {
  color: rgba(255, 255, 255, 0.85);
  font-size: 17px;
  line-height: 1.7;
  margin-bottom: 30px;
  text-align: justify;
}

.gallery2-modal-actions {
  display: flex;
  gap: 18px;
  margin-bottom: 35px;
}

.gallery2-modal-btn {
  padding: 18px 28px;
  border: none;
  border-radius: 50px;
  font-weight: 700;
  font-size: 15px;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  cursor: pointer;
  transition: all 0.3s ease;
  flex: 1;
  position: relative;
  overflow: hidden;
}

.gallery2-modal-btn-primary {
  background: linear-gradient(45deg, #800000, #000000);
  color: white;
  box-shadow: 0 12px 24px rgba(128, 0, 0, 0.4);
}

.gallery2-modal-btn-primary:hover {
  transform: translateY(-4px);
  box-shadow: 0 18px 35px rgba(128, 0, 0, 0.5);
}

.gallery2-modal-counter {
  color: rgba(255, 255, 255, 0.7);
  font-size: 16px;
  font-weight: 600;
  text-align: center;
  text-transform: uppercase;
  letter-spacing: 3px;
  padding: 12px 24px;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 25px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Swipe Indicator */
.swipe-indicator {
  position: fixed;
  bottom: 30px;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(10px);
  color: white;
  padding: 12px 20px;
  border-radius: 25px;
  font-size: 14px;
  font-weight: 500;
  text-align: center;
  z-index: 999;
  opacity: 1;
  transition: opacity 0.5s ease;
  border: 1px solid rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  gap: 8px;
}

.swipe-indicator.hidden {
  opacity: 0;
  pointer-events: none;
}

.swipe-indicator .swipe-icon {
  display: inline-flex;
  align-items: center;
  animation: swipeAnimation 2s infinite;
}

@keyframes swipeAnimation {
  0%, 100% {
    transform: translateX(0);
  }
  25% {
    transform: translateX(-5px);
  }
  75% {
    transform: translateX(5px);
  }
}

.swipe-arrows {
  display: flex;
  align-items: center;
  gap: 4px;
}

.swipe-arrow {
  font-size: 16px;
  opacity: 0.8;
}

/* Desktop Override - popup at bottom for large screens */
@media (min-width: 1025px) {
  .card-info {
    top: auto;
    bottom: 80px;
    left: 50%;
    transform: translateX(-50%);
  }
}

/* Responsive Design */
@media (max-width: 1024px) and (min-width: 769px) {
  body {
    padding: 30px 20px;
    justify-content: center;
  }

  .header {
    margin-bottom: 50px;
    margin-top: 0;
  }

  .main-title {
    font-size: 56px;
    letter-spacing: 5px;
    margin: 18px 0 35px 0;
  }
}

@media (max-width: 768px) {
  body {
    padding: 15px 10px;
    justify-content: flex-start;
    background-size: 200px 150px;
    width: 100dvw;
    height: 100dvh;
  }

  .gallery2-modal-overlay {
    width: 100dvw;
    height: 100dvh;
  }

  .header {
    margin-bottom: 30px;
    margin-top: 2rem;
    transform: scale(2);
  }

  .gallery2-page {
    padding: 15px 10px;
  }

  .main-title {
    font-size: 32px;
    letter-spacing: 3px;
    margin: 10px 0 25px 0;
    line-height: 1.1;
  }

  .subtitle {
    font-size: 12px;
    letter-spacing: 1.5px;
    margin-bottom: 12px;
  }

  .card {
    width: 160px;
    margin: 0 4px;
  }

  .slider-container {
    padding: 0 10px;
    max-width: 100%;
  }

  .slider-track {
    gap: 6px;
  }

  .card-info {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    min-width: calc(100vw - 40px);
    max-width: calc(100vw - 40px);
    padding: 1rem;
    margin: 0;
    border-radius: 12px;
    bottom: auto;
  }

  .card-info h2 {
    font-size: 22px;
    margin-bottom: 12px;
  }

  .card-info p {
    font-size: 14px;
    line-height: 1.5;
  }

  .close-btn {
    top: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    min-height: 44px;
    min-width: 44px;
  }

  .close-btn svg {
    width: 20px;
    height: 20px;
  }
}@media (max-width: 480px) {
  body {
    padding: 10px 8px;
    background-size: 150px 100px;
    width: 100dvw;
    height: 100dvh;
  }

  .gallery2-modal-overlay {
    width: 100dvw;
    height: 100dvh;
  }

  .header {
    margin-bottom: 20px;
    margin-top: 5px;
  }

  .main-title {
    font-size: 24px;
    letter-spacing: 1.5px;
    margin: 8px 0 20px 0;
    line-height: 1;
  }

  .subtitle {
    font-size: 10px;
    letter-spacing: 1px;
    margin-bottom: 10px;
  }

  .card {
    width: 130px;
    margin: 0 3px;
  }

  .slider-container {
    padding: 0 5px;
  }

  .slider-track {
    gap: 4px;
  }

  .card-info {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    min-width: calc(100vw - 30px);
    max-width: calc(100vw - 30px);
    padding: 0.8rem;
    margin: 0;
    border-radius: 10px;
    bottom: auto;
  }

  .card-info h2 {
    font-size: 18px;
    margin-bottom: 10px;
  }

  .card-info p {
    font-size: 12px;
    line-height: 1.4;
  }

  .close-btn {
    top: 15px;
    right: 15px;
    width: 48px;
    height: 48px;
    min-height: 44px;
    min-width: 44px;
  }

  .close-btn svg {
    width: 18px;
    height: 18px;
  }
}/* Extra Small Mobile */
@media (max-width: 360px) {
  body {
    padding: 8px 5px;
    background-size: 120px 80px;
    width: 100dvw;
    height: 100dvh;
  }

  .gallery2-modal-overlay {
    width: 100dvw;
    height: 100dvh;
  }

  .header {
    margin-bottom: 15px;
    margin-top: 0;
  }

  .main-title {
    font-size: 20px;
    letter-spacing: 1px;
    margin: 5px 0 15px 0;
  }

  .subtitle {
    font-size: 9px;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
  }

  .card {
    width: 110px;
    margin: 0 2px;
  }

  .slider-container {
    padding: 0 3px;
  }

  .slider-track {
    gap: 3px;
  }

  .card-info {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    min-width: calc(100vw - 20px);
    max-width: calc(100vw - 20px);
    padding: 0.7rem;
    margin: 0;
    border-radius: 8px;
    bottom: auto;
  }

  .card-info h2 {
    font-size: 16px;
    margin-bottom: 8px;
  }

  .card-info p {
    font-size: 11px;
    line-height: 1.3;
  }

  .close-btn {
    top: 10px;
    right: 10px;
    width: 46px;
    height: 46px;
    min-height: 44px;
    min-width: 44px;
  }

  .close-btn svg {
    width: 16px;
    height: 16px;
  }
}

/* Responsive for Gallery2 Modal */
@media (max-width: 1024px) {
  .gallery2-modal-content {
    flex-direction: column;
    max-width: 95vw;
    max-height: 95vh;
  }

  .gallery2-modal-info {
    flex: none;
    padding: 35px;
  }

  .gallery2-modal-title {
    font-size: 32px;
  }

  .gallery2-modal-image-container {
    min-height: 400px;
  }
}

@media (max-width: 768px) {
  .gallery2-modal-content {
    max-width: 95vw;
    max-height: 90vh;
    margin: 20px;
  }

  .gallery2-modal-close {
    top: 15px;
    right: 15px;
    width: 50px;
    height: 50px;
    min-height: 44px;
    min-width: 44px;
    -webkit-tap-highlight-color: transparent;
    touch-action: manipulation;
  }

  .gallery2-modal-info {
    padding: 25px;
  }

  .gallery2-modal-title {
    font-size: 24px;
  }

  .gallery2-modal-description {
    font-size: 14px;
    line-height: 1.5;
  }

  .gallery2-modal-actions {
    flex-direction: column;
    gap: 10px;
  }

  .gallery2-modal-image-container {
    min-height: 250px;
  }
}

@media (max-width: 480px) {
  .gallery2-modal-content {
    max-width: 98vw;
    max-height: 85vh;
    margin: 10px;
    border-radius: 15px;
  }

  .gallery2-modal-close {
    top: 10px;
    right: 10px;
    width: 46px;
    height: 46px;
  }

  .gallery2-modal-nav {
    width: 50px;
    height: 50px;
    background: rgba(0, 0, 0, 0.95);
    border: 3px solid white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
    font-size: 16px;
    font-weight: bold;
  }

  .gallery2-modal-nav:hover {
    background: rgba(128, 0, 0, 1);
    transform: translateY(-50%) scale(1.05);
  }

  .gallery2-modal-prev {
    left: 10px !important;
    display: flex !important;
    visibility: visible !important;
    opacity: 1 !important;
  }

  .gallery2-modal-next {
    right: 10px !important;
    display: flex !important;
    visibility: visible !important;
    opacity: 1 !important;
  }

  .gallery2-modal-info {
    padding: 20px;
  }

  .gallery2-modal-title {
    font-size: 20px;
    margin-bottom: 10px;
  }

  .gallery2-modal-description {
    font-size: 13px;
    line-height: 1.4;
  }

  .gallery2-modal-image-container {
    min-height: 200px;
  }

  .gallery2-modal-image {
    padding: 15px;
  }
}

@media (max-width: 360px) {
  .gallery2-modal-content {
    max-width: 100vw;
    max-height: 80vh;
    margin: 5px;
    border-radius: 12px;
  }

  .gallery2-modal-close {
    top: 8px;
    right: 8px;
    width: 44px;
    height: 44px;
  }

  .gallery2-modal-nav {
    width: 48px;
    height: 48px;
    background: rgba(0, 0, 0, 0.95);
    border: 3px solid white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.8);
    font-size: 14px;
    font-weight: bold;
  }

  .gallery2-modal-nav:hover {
    background: rgba(128, 0, 0, 1);
  }

  .gallery2-modal-prev {
    left: 8px !important;
    display: flex !important;
    visibility: visible !important;
    opacity: 1 !important;
  }

  .gallery2-modal-next {
    right: 8px !important;
    display: flex !important;
    visibility: visible !important;
    opacity: 1 !important;
  }

  .gallery2-modal-info {
    padding: 15px;
  }

  .gallery2-modal-title {
    font-size: 18px;
    margin-bottom: 8px;
  }

  .gallery2-modal-description {
    font-size: 12px;
  }

  .gallery2-modal-image-container {
    min-height: 180px;
  }

  .gallery2-modal-image {
    padding: 10px;
  }
}
}

@media (max-width: 480px) {
  .gallery2-modal-info {
    padding: 25px;
  }

  .gallery2-modal-title {
    font-size: 24px;
  }

  .gallery2-modal-description {
    font-size: 14px;
  }

  .gallery2-modal-close {
    top: 20px;
    right: 20px;
    width: 45px;
    height: 45px;
  }

  .gallery2-modal-nav {
    width: 55px;
    height: 55px;
    background: rgba(0, 0, 0, 0.9);
    border: 2px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
    font-size: 18px;
  }

  .gallery2-modal-nav:hover {
    background: rgba(128, 0, 0, 1);
    border-color: white;
  }

  .gallery2-modal-prev {
    left: 15px;
  }

  .gallery2-modal-next {
    right: 15px;
  }

  .gallery2-modal-btn {
    padding: 15px 22px;
    font-size: 13px;
  }
}

/* About Section Styling */
.about-section {
  width: 100%;
  background: #ffffff;
  padding: 0;
  position: relative;
  z-index: 2;
  font-family: 'Poppins', sans-serif;
}

.about-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0;
}

/* Brand Welcome Section */
.hoodie-section {
  padding: 80px 40px;
  background: #ffffff;
}

.hoodie-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
}

.hoodie-info {
  position: relative;
}

.product-badge {
  position: absolute;
  top: -20px;
  right: 0;
  font-size: 120px;
  font-weight: 200;
  color: #f5f5f5;
  line-height: 1;
  z-index: 0;
}

.hoodie-title {
  font-size: 80px;
  font-weight: 300;
  color: #000;
  margin: 0 0 20px 0;
  line-height: 0.9;
  position: relative;
  z-index: 1;
  font-family: 'Playfair Display', serif;
  font-style: italic;
}

.hoodie-price {
  font-size: 24px;
  font-weight: 600;
  color: #000;
  margin-bottom: 30px;
  font-family: 'Poppins', sans-serif;
}

.hoodie-description {
  font-size: 16px;
  line-height: 1.6;
  color: #666;
  margin-bottom: 40px;
  max-width: 400px;
  font-family: 'Poppins', sans-serif;
}

.hoodie-colors {
  display: flex;
  gap: 12px;
  margin-bottom: 40px;
}

.color-dot {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #000;
  cursor: pointer;
  position: relative;
}

.color-dot.gray {
  background: #ccc;
}

.color-dot.active::after {
  content: '';
  position: absolute;
  top: -3px;
  left: -3px;
  right: -3px;
  bottom: -3px;
  border: 2px solid #000;
  border-radius: 50%;
}

.hoodie-actions {
  display: flex;
  gap: 20px;
}

.btn-add-cart, .btn-view, .btn-explore, .btn-discover, .btn-explore-brand,
.btn-discover-quality, .btn-discover-innovation, .btn-learn-more {
  padding: 16px 32px;
  border: none;
  border-radius: 0;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  text-decoration: none;
  display: inline-block;
}

.btn-add-cart, .btn-explore, .btn-discover, .btn-discover-quality, .btn-learn-more {
  background: #000;
  color: #fff;
}

.btn-add-cart:hover, .btn-explore:hover, .btn-discover:hover, .btn-discover-quality:hover, .btn-learn-more:hover {
  background: #333;
  color: #fff;
}

.btn-view, .btn-explore-brand, .btn-discover-innovation {
  background: transparent;
  color: #000;
  border: 1px solid #000;
}

.btn-view:hover, .btn-explore-brand:hover, .btn-discover-innovation:hover {
  background: #000;
  color: #fff;
}

.hoodie-image {
  text-align: center;
}

.hoodie-img {
  width: 100%;
  max-width: 400px;
  height: auto;
  object-fit: contain;
}

/* Our Brands Section */
.brands-showcase-section {
  padding: 80px 40px;
  background: #f8f8f8;
}

.section-title {
  font-size: 60px;
  font-weight: 300;
  color: #000;
  margin-bottom: 40px;
  line-height: 1;
  font-family: 'Playfair Display', serif;
  font-style: italic;
}

.brands-preview {
  display: flex;
  gap: 20px;
  margin-bottom: 40px;
  justify-content: center;
}

.brand-preview {
  width: 60px;
  height: 80px;
  border-radius: 8px;
  overflow: hidden;
}

.brand-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.brands-main {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 60px;
  align-items: center;
}

.brands-image {
  text-align: center;
  background: #e8e8e8;
  border-radius: 20px;
  padding: 40px;
}

.main-brand-image {
  width: 100%;
  max-width: 300px;
  height: auto;
  object-fit: contain;
}

.brand-featured-title {
  font-size: 24px;
  font-weight: 600;
  color: #000;
  margin-bottom: 20px;
  font-family: 'Playfair Display', serif;
  font-style: italic;
}

.brand-description {
  font-size: 16px;
  line-height: 1.6;
  color: #666;
  margin-bottom: 30px;
  font-family: 'Poppins', sans-serif;
}

.brand-actions {
  display: flex;
  gap: 16px;
}

/* Featured Items Section */
.featured-items-section {
  padding: 80px 40px;
  background: #ffffff;
}

.feature-item-large {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  margin-bottom: 80px;
  align-items: center;
}

.feature-item-large:nth-child(even) {
  grid-template-columns: 1fr 1fr;
}

.feature-item-large:nth-child(even) .feature-image-large {
  order: 2;
}

.feature-item-large:nth-child(even) .feature-content-large {
  order: 1;
}

.feature-image-large {
  text-align: center;
}

.feature-img-large {
  width: 100%;
  max-width: 350px;
  height: auto;
  object-fit: contain;
}

.feature-content-large {
  position: relative;
}

.feature-number-large {
  font-size: 120px;
  font-weight: 200;
  color: #f5f5f5;
  position: absolute;
  top: -40px;
  right: 0;
  z-index: 0;
}

.feature-title {
  font-size: 48px;
  font-weight: 300;
  color: #000;
  margin-bottom: 24px;
  line-height: 1.1;
  position: relative;
  z-index: 1;
  font-family: 'Playfair Display', serif;
  font-style: italic;
}

.feature-desc {
  font-size: 16px;
  line-height: 1.6;
  color: #666;
  margin-bottom: 40px;
  max-width: 400px;
  font-family: 'Poppins', sans-serif;
}

.feature-actions {
  display: flex;
  gap: 16px;
}

.btn-learn-more, .btn-add-wishlist {
  padding: 16px 32px;
  border: none;
  border-radius: 0;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.btn-learn-more {
  background: #000;
  color: #fff;
}

.btn-add-wishlist {
  background: transparent;
  color: #000;
  border: 1px solid #000;
}

/* Brand Portfolio Showcase */
.featured-products-section {
  padding: 80px 40px;
  background: #ffffff;
  text-align: center;
}

.featured-products-title {
  font-size: 48px;
  font-weight: 300;
  color: #000;
  margin-bottom: 16px;
  line-height: 1.2;
  font-family: 'Playfair Display', serif;
  font-style: italic;
}

.featured-subtitle {
  font-size: 16px;
  color: #666;
  margin-bottom: 60px;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
  font-family: 'Poppins', sans-serif;
}

.products-showcase {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 40px;
  margin-bottom: 40px;
}

.product-card-featured {
  text-align: center;
}

.product-image-featured {
  margin-bottom: 20px;
  overflow: hidden;
  border-radius: 8px;
}

.product-image-featured img {
  width: 100%;
  height: 300px;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.product-card-featured:hover .product-image-featured img {
  transform: scale(1.05);
}

.product-name-featured {
  font-size: 16px;
  font-weight: 500;
  color: #000;
  margin-bottom: 8px;
}

.product-category-featured {
  font-size: 14px;
  font-weight: 400;
  color: #666;
  font-style: italic;
}

.pagination-dots {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin-top: 40px;
}

.dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #ddd;
  cursor: pointer;
  transition: all 0.3s ease;
}

.dot:hover {
  background: #999;
  transform: scale(1.2);
}

.dot.active {
  background: #000;
  width: 32px;
  border-radius: 6px;
}

/* Brand Filter */
.brand-filter {
  display: flex;
  justify-content: center;
  gap: 16px;
  flex-wrap: wrap;
  margin-bottom: 40px;
}

.filter-item {
  padding: 10px 24px;
  background: transparent;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 14px;
  font-weight: 500;
  color: #666;
}

.filter-item:hover {
  border-color: #000;
  color: #000;
}

.filter-item.active {
  background: #000;
  color: #fff;
  border-color: #000;
}

/* Fade in animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive Design */
@media (max-width: 1024px) {
  .hoodie-content,
  .newbie-main,
  .feature-item-large {
    grid-template-columns: 1fr;
    gap: 40px;
    text-align: center;
  }

  .hoodie-section,
  .newbie-section,
  .featured-items-section,
  .featured-products-section {
    padding: 60px 30px;
  }

  .hoodie-title,
  .section-title,
  .feature-title,
  .featured-products-title,
  .newsletter-title-final {
    font-size: 48px;
  }

  .products-showcase {
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
  }

  .brand-filter {
    gap: 20px;
  }
}

@media (max-width: 768px) {
  .hoodie-section,
  .newbie-section,
  .featured-items-section,
  .featured-products-section {
    padding: 40px 20px;
  }

  .hoodie-title {
    font-size: 48px;
  }

  .section-title,
  .feature-title,
  .featured-products-title {
    font-size: 36px;
  }

  .product-badge,
  .feature-number-large {
    font-size: 80px;
  }

  .products-showcase {
    grid-template-columns: 1fr;
    gap: 30px;
  }

  .brand-filter {
    flex-direction: column;
    gap: 16px;
  }

  .newbie-products {
    flex-wrap: wrap;
    gap: 12px;
  }

  .mini-product {
    width: 50px;
    height: 70px;
  }

  .newsletter-form-final {
    flex-direction: column;
    gap: 12px;
  }

  .hoodie-actions,
  .newbie-actions,
  .feature-actions {
    flex-direction: column;
    gap: 12px;
  }

  .btn-add-cart,
  .btn-view,
  .btn-learn-more,
  .btn-add-wishlist {
    width: 100%;
    text-align: center;
  }
}
@endsection

@section('content')
<div class="gallery2-page">
  <!-- API Error Notification -->
  <div id="apiErrorNotification" class="fixed z-50 px-4 py-2 text-white bg-red-600 rounded-lg shadow-lg top-4 right-4" style="display: none;">
    <div class="flex items-center space-x-2">
      <span>⚠️</span>
      <span class="text-sm">API offline - Using cached data</span>
    </div>
  </div>

  <div class="header">
    <p class="subtitle"></p>
    <h1 class="main-title"></h1>
  </div>

  <div class="slider-container" id="sliderContainer">
    <div class="slider-track" id="sliderTrack">
      <!-- Gallery items will be loaded here via JavaScript -->
    </div>
  </div>

  <!-- Swipe Indicator -->
  <div class="swipe-indicator" id="swipeIndicator">
    <div class="swipe-arrows">
      <span class="swipe-arrow">←</span>
      <span class="swipe-arrow">→</span>
    </div>
    <span>Geser untuk navigasi</span>
  </div>

  <button class="close-btn" id="closeBtn">
    <svg viewBox="0 0 24 24" fill="none">
      <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
    </svg>
  </button>

  <div class="card-info" id="cardInfo">
    <h2 id="cardTitle"></h2>
    <p id="cardDesc"></p>
  </div>

  <!-- Modal Popup -->
  <div class="gallery2-modal-overlay" id="modalOverlay" style="display: none;">
    <div class="gallery2-modal-content" onclick="event.stopPropagation();">
      <button class="gallery2-modal-close" id="modalClose">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <div class="gallery2-modal-image-container">
        <button class="gallery2-modal-nav gallery2-modal-prev" id="modalPrev">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <div class="gallery2-modal-image">
          <img id="modalImage" src="" alt="" class="object-contain w-full h-full" />
        </div>

        <button class="gallery2-modal-nav gallery2-modal-next" id="modalNext">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>

      <div class="gallery2-modal-info">
        <div class="gallery2-modal-category" id="modalCategory"></div>
        <h2 class="gallery2-modal-title" id="modalTitle"></h2>
        <p class="gallery2-modal-description" id="modalDescription"></p>
        <div class="gallery2-modal-actions">
          <button class="gallery2-modal-btn gallery2-modal-btn-primary" id="modalVisitBtn">
            Kunjungi
          </button>
        </div>
        <div class="gallery2-modal-counter" id="modalCounter">
          1 / 1
        </div>
      </div>
    </div>
  </div>
</div>

<!-- About Section -->
<div class="about-section">
  <div class="about-container">

    <!-- Brand Welcome Section -->
    <div class="brand-welcome-section">
      <div class="welcome-content">
        <div class="welcome-info">
          <div class="welcome-badge">Selamat Datang</div>
          <h1 class="welcome-title">VANY GROUP</h1>
          <div class="welcome-tagline">Keunggulan Tradisi & Inovasi Modern</div>
          <p class="welcome-description">
            VANY GROUP adalah rumah bagi brand-brand premium yang menggabungkan kekayaan warisan budaya Indonesia
            dengan desain kontemporer. Dari fashion hingga hospitality, setiap brand kami mencerminkan komitmen
            terhadap kualitas, kerajinan tangan, dan pengalaman pelanggan yang luar biasa.
          </p>
          <div class="brand-highlights">
            <div class="highlight-item">
              <span class="highlight-number">3+</span>
              <span class="highlight-text">Brand Premium</span>
            </div>
            <div class="highlight-item">
              <span class="highlight-number">100%</span>
              <span class="highlight-text">Kualitas Terjamin</span>
            </div>
          </div>
          <div class="welcome-actions">
            <a href="#brands-section" class="btn-explore">Jelajahi Brand</a>
          </div>
        </div>
        <div class="welcome-image">
          @if(isset($products) && $products->isNotEmpty())
            <img src="{{ $products->first()->image ?? 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&h=700&fit=crop&crop=center' }}" alt="Featured Product" class="welcome-img">
          @else
            <img src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&h=700&fit=crop&crop=center" alt="VANY GROUP" class="welcome-img">
          @endif
        </div>
      </div>
    </div>

    <!-- Our Brands Section -->
    <div class="brands-showcase-section" id="brands-section">
      <h2 class="section-title">Brand Kami</h2>
      <div class="brands-preview">
        @if(isset($categories) && $categories->isNotEmpty())
          @foreach($categories->take(4) as $category)
            <div class="brand-preview">
              <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=100&h=120&fit=crop&crop=center' }}" alt="{{ $category->name }}">
            </div>
          @endforeach
        @else
          <div class="brand-preview">
            <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=100&h=120&fit=crop&crop=center" alt="VNY">
          </div>
          <div class="brand-preview">
            <img src="https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=100&h=120&fit=crop&crop=center" alt="VanySongket">
          </div>
          <div class="brand-preview">
            <img src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=100&h=120&fit=crop&crop=center" alt="VanyVilla">
          </div>
          <div class="brand-preview">
            <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=100&h=120&fit=crop&crop=center" alt="Coming Soon">
          </div>
        @endif
      </div>
      <div class="brands-main">
        <div class="brands-image">
          @if(isset($brands) && $brands->isNotEmpty())
            @php $featuredBrand = $brands->first(); @endphp
            <img src="{{ $featuredBrand->hero_data['image'] ?? 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=600&h=400&fit=crop&crop=center' }}" alt="{{ $featuredBrand->title }}" class="main-brand-image">
          @else
            <img src="https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=600&h=400&fit=crop&crop=center" alt="VANY GROUP Collection" class="main-brand-image">
          @endif
        </div>
        <div class="brands-info">
          @if(isset($brands) && $brands->isNotEmpty())
            @php $featuredBrand = $brands->first(); @endphp
            <h3 class="brand-featured-title">{{ $featuredBrand->title }}</h3>
            <p class="brand-description">
              {{ $featuredBrand->description ?? 'Discover premium quality products with meticulous attention to detail and traditional craftsmanship.' }}
            </p>
          @else
            <h3 class="brand-featured-title">Koleksi Premium VANY GROUP</h3>
            <p class="brand-description">
              Temukan produk berkualitas premium dengan perhatian detail yang teliti dan kerajinan tradisional yang dipadukan dengan sentuhan modern untuk gaya hidup Anda.
            </p>
          @endif
          <div class="brand-actions">
            <a href="/vny" class="btn-explore-brand">Jelajahi Brand VNY</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Brand Values -->
    <div class="featured-items-section">
      <div class="feature-item-large">
        <div class="feature-image-large">
          @if(isset($categories) && $categories->isNotEmpty())
            <img src="{{ $categories->first()->image ?? 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=500&h=700&fit=crop&crop=center' }}" alt="Quality Craftsmanship" class="feature-img-large">
          @else
            <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=500&h=700&fit=crop&crop=center" alt="Quality Craftsmanship" class="feature-img-large">
          @endif
        </div>
        <div class="feature-content-large">
          <div class="feature-number-large">01</div>
          <h3 class="feature-title">Kualitas Kerajinan Tangan</h3>
          <p class="feature-desc">
            Setiap produk dalam koleksi VANY GROUP merepresentasikan komitmen kami terhadap kualitas luar biasa
            dengan perhatian detail yang cermat. Kami menggabungkan teknik tradisional warisan budaya Indonesia
            dengan inovasi modern untuk menciptakan produk yang timeless dan berkelas.
          </p>
          <div class="feature-actions">
            <a href="#portfolio-section" class="btn-learn-more">Pelajari Lebih Lanjut</a>
          </div>
        </div>
      </div>

      <div class="feature-item-large">
        <div class="feature-image-large">
          @if(isset($categories) && $categories->count() > 1)
            <img src="{{ $categories->skip(1)->first()->image ?? 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=500&h=700&fit=crop&crop=center' }}" alt="Heritage Design" class="feature-img-large">
          @else
            <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=500&h=700&fit=crop&crop=center" alt="Heritage Design" class="feature-img-large">
          @endif
        </div>
        <div class="feature-content-large">
          <div class="feature-number-large">02</div>
          <h3 class="feature-title">Warisan Budaya & Inovasi</h3>
          <p class="feature-desc">
            Menjembatani warisan budaya Indonesia yang kaya dengan desain kontemporer, brand-brand kami
            merayakan kekayaan budaya lokal sambil memenuhi kebutuhan gaya hidup modern. VNY untuk fashion
            kontemporer, VanySongket untuk kain tradisional berkualitas, dan VanyVilla untuk pengalaman
            hospitality yang berkesan.
          </p>
          <div class="feature-actions">
            <a href="/vny/about" class="btn-learn-more">Jelajahi Warisan</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Brand Portfolio Showcase -->
    <div class="featured-products-section" id="portfolio-section">
      <h2 class="featured-products-title">Portofolio Brand Kami</h2>
      <p class="featured-subtitle">Jelajahi beragam brand di bawah naungan VANY GROUP, masing-masing merepresentasikan keunggulan di bidangnya</p>

      <!-- Brand Category Filter -->
      <div class="brand-filter">
        @if(isset($categories) && $categories->isNotEmpty())
          @foreach($categories->take(7) as $index => $category)
            <div class="filter-item {{ $index == 0 ? 'active' : '' }}">{{ $category->name }}</div>
          @endforeach
        @else
          <div class="filter-item active">VNY</div>
          <div class="filter-item">VanySongket</div>
          <div class="filter-item">VanyVilla</div>
          <div class="filter-item">Heritage</div>
          <div class="filter-item">Modern</div>
          <div class="filter-item">Traditional</div>
          <div class="filter-item">New</div>
        @endif
      </div>

      <div class="products-showcase">
        @if(isset($products) && $products->isNotEmpty())
          @foreach($products->take(6) as $product)
            <div class="product-card-featured">
              <div class="product-image-featured">
                <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=250&h=350&fit=crop&crop=center' }}" alt="{{ $product->name }}">
              </div>
              <h4 class="product-name-featured">{{ $product->name }}</h4>
              <p class="product-category-featured">{{ $product->category->name ?? 'Premium Collection' }}</p>
            </div>
          @endforeach
        @else
          <div class="product-card-featured">
            <div class="product-image-featured">
              <img src="https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=250&h=350&fit=crop&crop=center" alt="VNY Collection">
            </div>
            <h4 class="product-name-featured">VNY Fashion</h4>
            <p class="product-category-featured">Fashion Kontemporer</p>
          </div>

          <div class="product-card-featured">
            <div class="product-image-featured">
              <img src="https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=250&h=350&fit=crop&crop=center" alt="VanySongket">
            </div>
            <h4 class="product-name-featured">VanySongket</h4>
            <p class="product-category-featured">Kain Tradisional Premium</p>
          </div>

          <div class="product-card-featured">
            <div class="product-image-featured">
              <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=250&h=350&fit=crop&crop=center" alt="VanyVilla">
            </div>
            <h4 class="product-name-featured">VanyVilla</h4>
            <p class="product-category-featured">Hospitality & Villa</p>
          </div>

          <div class="product-card-featured">
            <div class="product-image-featured">
              <img src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=250&h=350&fit=crop&crop=center" alt="Premium Line">
            </div>
            <h4 class="product-name-featured">Koleksi Premium</h4>
            <p class="product-category-featured">Seri Eksklusif</p>
          </div>

          <div class="product-card-featured">
            <div class="product-image-featured">
              <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=250&h=350&fit=crop&crop=center" alt="Modern Design">
            </div>
            <h4 class="product-name-featured">Desain Kontemporer</h4>
            <p class="product-category-featured">Gaya Modern</p>
          </div>

          <div class="product-card-featured">
            <div class="product-image-featured">
              <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=250&h=350&fit=crop&crop=center" alt="Limited Edition">
            </div>
            <h4 class="product-name-featured">Edisi Terbatas</h4>
            <p class="product-category-featured">Koleksi Eksklusif</p>
          </div>
        @endif
      </div>

      <!-- Portfolio Navigation -->
      <div class="pagination-dots">
        <div class="dot active"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
    </div>

  </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load homepage constants and gallery items
    let GALLERY_ITEMS = [];
    let selectedItem = null;
    let currentIndex = 0;
    let hasInteracted = false;
    const swipeIndicator = document.getElementById('swipeIndicator');

    // Hide swipe indicator after user interaction
    function hideSwipeIndicator() {
        if (!hasInteracted) {
            hasInteracted = true;
            swipeIndicator.classList.add('hidden');
        }
    }

    // Show swipe indicator initially and hide after 5 seconds if no interaction
    setTimeout(() => {
        if (!hasInteracted) {
            hideSwipeIndicator();
        }
    }, 5000);

    // Load data from API
    fetch('https://vanygroup.id/api/vny/homepage/constants')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                GALLERY_ITEMS = data.data.GALLERY_ITEMS || [];

                // Update header
                if (data.data.HERO_SECTION) {
                    document.querySelector('.subtitle').textContent = data.data.HERO_SECTION.SUBTITLE || 'The power of batak fashion';
                    document.querySelector('.main-title').textContent = data.data.HERO_SECTION.TITLE || 'Vany GROUP';
                }

                // Initialize slider
                initSlider();
            } else {
                // Show error notification
                document.getElementById('apiErrorNotification').style.display = 'block';
                // Load fallback data or show empty state
                console.warn('API offline, using fallback data');
                loadFallbackData();
            }
        })
        .catch(error => {
            console.error('Error loading homepage constants:', error);
            document.getElementById('apiErrorNotification').style.display = 'block';
            loadFallbackData();
        });

    function loadFallbackData() {
        // Fallback gallery items for when API is offline
        GALLERY_ITEMS = [
            {
                id: 1,
                title: "VNY Store",
                description: "Premium sneakers collection",
                image: "/images/vny-store.jpg",
                category: "Fashion",
                target: "/vny"
            },
            {
                id: 2,
                title: "Gallery",
                description: "Our latest collections",
                image: "/images/gallery.jpg",
                category: "Collections",
                target: "/gallery"
            },
            {
                id: 3,
                title: "About Us",
                description: "Learn more about VNY",
                image: "/images/about.jpg",
                category: "Company",
                target: "/about"
            }
        ];
        initSlider();
    }

    function initSlider() {
        if (!window.gsap || GALLERY_ITEMS.length === 0) {
            if (!window.gsap) console.warn('GSAP not loaded yet');
            if (GALLERY_ITEMS.length === 0) console.warn('No gallery items available');
            return;
        }

        // Store gallery data in window scope for CircularSlider access
        window.galleryData = GALLERY_ITEMS;

        // Create gallery cards
        const track = document.getElementById('sliderTrack');
        track.innerHTML = '';

        GALLERY_ITEMS.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'card';
            card.setAttribute('data-title', item.title);
            card.setAttribute('data-desc', item.description);

            card.innerHTML = `
                <img src="${item.image}" alt="${item.title}" class="object-cover w-full h-full" onerror="this.src='/images/placeholder.jpg'">
                <div class="hover-overlay"><span>Click to see more</span></div>
            `;

            track.appendChild(card);
        });

        // Initialize GSAP CircularSlider
        new CircularSlider();
    }

    function handleItemClick(item, index) {
        selectedItem = item;
        currentIndex = index;
        showModal();
    }

    function showModal() {
        if (!selectedItem) return;

        const modal = document.getElementById('modalOverlay');
        const modalImage = document.getElementById('modalImage');
        const modalCategory = document.getElementById('modalCategory');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        const modalCounter = document.getElementById('modalCounter');
        const modalVisitBtn = document.getElementById('modalVisitBtn');

        modalImage.src = selectedItem.image;
        modalImage.alt = selectedItem.title;
        modalCategory.textContent = selectedItem.category;
        modalTitle.textContent = selectedItem.title;
        modalDescription.textContent = selectedItem.description;
        modalCounter.textContent = `${currentIndex + 1} / ${GALLERY_ITEMS.length}`;

        modalVisitBtn.onclick = () => {
            window.location.href = selectedItem.target;
        };

        modal.style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalOverlay').style.display = 'none';
        selectedItem = null;
    }

    function handleNext() {
        if (GALLERY_ITEMS.length === 0) return;
        currentIndex = (currentIndex + 1) % GALLERY_ITEMS.length;
        selectedItem = GALLERY_ITEMS[currentIndex];
        showModal();
    }

    function handlePrev() {
        if (GALLERY_ITEMS.length === 0) return;
        currentIndex = (currentIndex - 1 + GALLERY_ITEMS.length) % GALLERY_ITEMS.length;
        selectedItem = GALLERY_ITEMS[currentIndex];
        showModal();
    }

    // Modal event listeners
    document.getElementById('modalOverlay').addEventListener('click', closeModal);
    document.getElementById('modalClose').addEventListener('click', closeModal);
    document.getElementById('modalNext').addEventListener('click', handleNext);
    document.getElementById('modalPrev').addEventListener('click', handlePrev);

    // GSAP CircularSlider class (updated positions)
    const positions = [
        {
            height: 620,
            z: 220,
            rotateY: 48,
            y: 0,
            clip: "polygon(0px 0px, 100% 10%, 100% 90%, 0px 100%)"
        },
        {
            height: 580,
            z: 165,
            rotateY: 35,
            y: 0,
            clip: "polygon(0px 0px, 100% 8%, 100% 92%, 0px 100%)"
        },
        {
            height: 495,
            z: 110,
            rotateY: 15,
            y: 0,
            clip: "polygon(0px 0px, 100% 7%, 100% 93%, 0px 100%)"
        },
        {
            height: 420,
            z: 66,
            rotateY: 15,
            y: 0,
            clip: "polygon(0px 0px, 100% 7%, 100% 93%, 0px 100%)"
        },
        {
            height: 353,
            z: 46,
            rotateY: 6,
            y: 0,
            clip: "polygon(0px 0px, 100% 7%, 100% 93%, 0px 100%)"
        },
        {
            height: 310,
            z: 0,
            rotateY: 0,
            y: 0,
            clip: "polygon(0 0, 100% 0, 100% 100%, 0 100%)"
        },
        {
            height: 353,
            z: 54,
            rotateY: 348,
            y: 0,
            clip: "polygon(0px 7%, 100% 0px, 100% 100%, 0px 93%)"
        },
        {
            height: 420,
            z: 89,
            rotateY: -15,
            y: 0,
            clip: "polygon(0px 7%, 100% 0px, 100% 100%, 0px 93%)"
        },
        {
            height: 495,
            z: 135,
            rotateY: -15,
            y: 1,
            clip: "polygon(0px 7%, 100% 0px, 100% 100%, 0px 93%)"
        },
        {
            height: 580,
            z: 195,
            rotateY: 325,
            y: 0,
            clip: "polygon(0px 8%, 100% 0px, 100% 100%, 0px 92%)"
        },
        {
            height: 620,
            z: 240,
            rotateY: 312,
            y: 0,
            clip: "polygon(0px 10%, 100% 0px, 100% 100%, 0px 90%)"
        }
    ];

    class CircularSlider {
        constructor() {
            this.container = document.getElementById("sliderContainer");
            this.track = document.getElementById("sliderTrack");
            this.cards = Array.from(document.querySelectorAll(".card"));
            this.totalCards = this.cards.length;
            this.isDragging = false;
            this.startX = 0;
            this.dragDistance = 0;
            this.threshold = 60;
            this.processedSteps = 0;
            this.expandedCard = null;
            this.cardInfo = document.getElementById("cardInfo");
            this.cardTitle = document.getElementById("cardTitle");
            this.cardDesc = document.getElementById("cardDesc");
            this.closeBtn = document.getElementById("closeBtn");
            this.cardClone = null;

            this.init();
        }

        init() {
            this.applyPositions();
            this.attachEvents();
        }

        applyPositions() {
            this.cards.forEach((card, index) => {
                const pos = positions[index] || positions[positions.length - 1];
                gsap.set(card, {
                    height: pos.height,
                    clipPath: pos.clip,
                    transform: `translateZ(${pos.z}px) rotateY(${pos.rotateY}deg) translateY(${pos.y}px)`
                });
            });
        }

        expandCard(card) {
            if (this.expandedCard) return;

            // Use the existing modal system instead of custom card expansion
            const index = this.cards.indexOf(card);
            const galleryData = window.galleryData || [];
            const item = galleryData[index];

            if (item) {
                selectedItem = item;
                currentIndex = index;
                showModal();
            }
        }

        closeCard() {
            // Use the existing modal close function
            closeModal();
            this.expandedCard = null;
        }

        rotate(direction) {
            if (this.expandedCard) return;

            // Hide swipe indicator on first interaction
            if (typeof hideSwipeIndicator === 'function') {
                hideSwipeIndicator();
            }

            this.cards.forEach((card, index) => {
                let newIndex;
                if (direction === "next") {
                    newIndex = (index - 1 + this.totalCards) % this.totalCards;
                } else {
                    newIndex = (index + 1) % this.totalCards;
                }

                const pos = positions[newIndex] || positions[positions.length - 1];

                gsap.set(card, { clipPath: pos.clip });

                gsap.to(card, {
                    height: pos.height,
                    duration: 0.5,
                    ease: "power2.out"
                });

                gsap.to(card, {
                    transform: `translateZ(${pos.z}px) rotateY(${pos.rotateY}deg) translateY(${pos.y}px)`,
                    duration: 0.5,
                    ease: "power2.out"
                });
            });

            if (direction === "next") {
                const firstCard = this.cards.shift();
                this.cards.push(firstCard);
                this.track.appendChild(firstCard);
            } else {
                const lastCard = this.cards.pop();
                this.cards.unshift(lastCard);
                this.track.prepend(lastCard);
            }
        }

        attachEvents() {
            this.cards.forEach((card) => {
                card.addEventListener("click", (e) => {
                    if (!this.isDragging && !this.expandedCard) {
                        this.expandCard(card);
                    }
                });
            });

            this.closeBtn.addEventListener("click", () => this.closeCard());

            this.container.addEventListener("mousedown", (e) =>
                this.handleDragStart(e)
            );
            this.container.addEventListener(
                "touchstart",
                (e) => this.handleDragStart(e),
                { passive: false }
            );

            document.addEventListener("mousemove", (e) => this.handleDragMove(e));
            document.addEventListener("touchmove", (e) => this.handleDragMove(e), {
                passive: false
            });

            document.addEventListener("mouseup", () => this.handleDragEnd());
            document.addEventListener("touchend", () => this.handleDragEnd());

            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape" && this.expandedCard) {
                    this.closeCard();
                } else if (e.key === "ArrowLeft" && !this.expandedCard) {
                    this.rotate("prev");
                } else if (e.key === "ArrowRight" && !this.expandedCard) {
                    this.rotate("next");
                }
            });
        }

        handleDragStart(e) {
            if (this.expandedCard) return;

            // Hide swipe indicator on first interaction
            if (typeof hideSwipeIndicator === 'function') {
                hideSwipeIndicator();
            }

            this.isDragging = true;
            this.container.classList.add("dragging");
            this.startX = e.type.includes("mouse") ? e.clientX : e.touches[0].clientX;
            this.dragDistance = 0;
            this.processedSteps = 0;
        }

        handleDragMove(e) {
            if (!this.isDragging) return;

            e.preventDefault();
            const currentX = e.type.includes("mouse")
                ? e.clientX
                : e.touches[0].clientX;
            this.dragDistance = currentX - this.startX;

            const steps = Math.floor(Math.abs(this.dragDistance) / this.threshold);

            if (steps > this.processedSteps) {
                const direction = this.dragDistance > 0 ? "prev" : "next";
                this.rotate(direction);
                this.processedSteps = steps;
            }
        }

        handleDragEnd() {
            if (!this.isDragging) return;

            this.isDragging = false;
            this.container.classList.remove("dragging");
        }
    }
});

// Smooth scroll for anchor links
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Portfolio filter functionality
    const filterItems = document.querySelectorAll('.brand-filter .filter-item');
    const portfolioCards = document.querySelectorAll('.product-card-featured');

    filterItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            filterItems.forEach(filter => filter.classList.remove('active'));

            // Add active class to clicked item
            this.classList.add('active');

            // For demo purposes, just show all cards
            // In production, you would filter based on category
            portfolioCards.forEach(card => {
                card.style.display = 'block';
            });
        });
    });

    // Portfolio pagination functionality
    const paginationDots = document.querySelectorAll('.pagination-dots .dot');
    const productsShowcase = document.querySelector('.products-showcase');

    if (paginationDots.length > 0 && productsShowcase) {
        let currentPage = 0;
        const cardsPerPage = 6;
        const totalCards = portfolioCards.length;
        const totalPages = Math.ceil(totalCards / cardsPerPage);

        function showPage(pageIndex) {
            const startIndex = pageIndex * cardsPerPage;
            const endIndex = startIndex + cardsPerPage;

            portfolioCards.forEach((card, index) => {
                if (index >= startIndex && index < endIndex) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.5s ease';
                } else {
                    card.style.display = 'none';
                }
            });

            // Update pagination dots
            paginationDots.forEach((dot, index) => {
                if (index === pageIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        paginationDots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                currentPage = index;
                showPage(currentPage);
            });
        });

        // Initialize first page
        showPage(0);
    }
});
</script>
@endsection
