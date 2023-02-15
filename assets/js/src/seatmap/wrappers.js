"use strict";

import {Rect} from "konva/lib/shapes/Rect";
import {Vector2d} from "konva/lib/types";
import {Stage} from "konva/lib/Stage";

export class StageWrapper extends Stage {
    oldPosition: Vector2d;
}

export class RectWrapper extends Rect {
    oldPosition: Vector2d;
}