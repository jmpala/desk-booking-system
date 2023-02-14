import * as PIXI from 'pixi.js';
import { Viewport } from 'pixi-viewport';

export const app = new PIXI.Application({
  view: document.querySelector('canvas'),
  width: window.innerWidth,
  height: window.innerHeight,
  antialias: true,
  autoDensity: true,
  backgroundColor: 0x0,
  resolution: window.devicePixelRatio,
});

export const viewport = new Viewport({
  screenWidth: window.innerWidth,
  screenHeight: window.innerHeight,
  worldWidth: 1000,
  worldHeight: 1000,
  divWheel: app.view,
  interaction: app.renderer.plugins.interaction,
})
    .drag()
    .pinch()
    .wheel()
    .decelerate();

app.stage.addChild(viewport);
app.ticker.start();

const onResize = () => {
  app.renderer.resize(window.innerWidth, window.innerHeight);
  viewport.resize(window.innerWidth, window.innerHeight, 1000, 1000);
};
window.addEventListener('resize', onResize);