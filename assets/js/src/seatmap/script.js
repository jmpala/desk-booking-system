// need a state of the bookings
// need to save changes into the DB
// need to update the state of the bookings on other ends

import { Graphics } from "pixi.js";
import { GraphPaper, GraphStyle } from "pixi-graphpaper";
import { viewport } from './seatmap';

const paper = new GraphPaper(GraphStyle.BLUEPRINT);
viewport.addChild(paper);

const box = new Graphics();
box.interactive = true;
box.beginFill(0xfff8dc, 0.85);
box.drawCircle(0, 0, 50);
box.position.set(viewport.screenWidth / 2, viewport.screenHeight / 2);
viewport.addChild(box);

let dragPoint;

const onDragStart = (event) => {
    event.stopPropagation();
    dragPoint = event.target.getLocalPosition(box.parent);
    dragPoint.x -= box.x;
    dragPoint.y -= box.y;
    box.parent.on("pointermove", onDragMove);
};

const onDragMove = (event) => {
    const newPoint = event.target.getLocalPosition(box.parent);
    box.x = newPoint.x - dragPoint.x;
    box.y = newPoint.y - dragPoint.y;
};

const onDragEnd = (event) => {
    event.stopPropagation();
    box.parent.off("pointermove", onDragMove);
};

box.on("pointerdown", onDragStart);
box.on("pointerup", onDragEnd);
box.on("pointerupoutside", onDragEnd);