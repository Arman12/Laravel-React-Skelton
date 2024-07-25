import React from "react";
import numeric from "numeric";
import repeter from "./repeter.png";
import signHere from "./signHere.png";
import signatuerImg from "./signatuerImg.gif";
import { postHttpRequest } from "./../../../axios";
export default class SignaturePad extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      hasError: this.props.hasError,
      showSignImg: true
    };
  }

  componentDidMount() {
    var FlashCanvas;
    var that = this;
    var stats = function (a) {
      var r = { mean: 0, variance: 0, deviation: 0 }, t = a.length;
      for (var m, s = 0, l = t; l--; s += a[l]);
      for (m = r.mean = s / t, l = t, s = 0; l--; s += Math.pow(a[l] - m, 2));
      return r.deviation = Math.sqrt(r.variance = s / t), r;
    }

    var generate141Matrix = function (N) {
      var result = [];
      for (var row = 0; row < N; row++) {
        var newRow = [];
        for (var col = 0; col < N; col++) {
          if (col == row) {
            newRow.push(4);
          } else if (Math.abs(row - col) == 1) {
            newRow.push(1);
          } else {
            newRow.push(0);
          };
        };
        result.push(newRow);
      }
      return result;
    };

    var generateConstantMatrix = function (sampledPoints) {
      var result = [];
      result.push(
        numeric.sub(numeric.mul(sampledPoints[1], 6),
          sampledPoints[0])
      );
      for (var i = 2; i < sampledPoints.length - 2; i++) {
        result.push(numeric.mul(sampledPoints[i], 6));
      }
      result.push(
        numeric.sub(numeric.mul(sampledPoints[sampledPoints.length - 2], 6),
          sampledPoints[sampledPoints.length - 1])
      );
      return result;
    }

    var convertBSplineControlPointsToBezierControlPoints = function (BSplinePoints) {
      var beziers = [];
      for (var i = 0; i < BSplinePoints.length - 1; i++) {

        if (i == 0) {
          var p0 = BSplinePoints[0];
        } else {
          var p0 = p3;
        }

        var p1 = numeric.add(numeric.mul(2 / 3, BSplinePoints[i]),
          numeric.mul(1 / 3, BSplinePoints[i + 1])
        );
        var p2 = numeric.add(numeric.mul(1 / 3, BSplinePoints[i]),
          numeric.mul(2 / 3, BSplinePoints[i + 1])
        );
        if (i == BSplinePoints.length - 2) {
          var p3 = BSplinePoints[BSplinePoints.length - 1];
        } else {
          var p3 = numeric.add(numeric.mul(1 / 6, BSplinePoints[i]),
            numeric.mul(2 / 3, BSplinePoints[i + 1]),
            numeric.mul(1 / 6, BSplinePoints[i + 2])
          );
        }

        var bezierSegmentControlPoints = [p0, p1, p2, p3];
        beziers.push(bezierSegmentControlPoints);
      }
      return beziers;
    };

    var getBezierControlPoints = function (sampledPoints) {
      if (sampledPoints.length < 4) {
        if (sampledPoints.length === 3) {
          beziers = [sampledPoints[0], sampledPoints[1], sampledPoints[1], sampledPoints[2]];
          return [beziers]
        } else if (sampledPoints.length === 2) {
          beziers = [sampledPoints[0], sampledPoints[0], sampledPoints[1], sampledPoints[1]]
          return [beziers]
        } else if (sampledPoints.length === 1) {
          beziers = [sampledPoints[0], sampledPoints[0], sampledPoints[0], sampledPoints[0]];
          return [beziers]
        };
      }
      var M = generate141Matrix(sampledPoints.length - 2);
      var C = generateConstantMatrix(sampledPoints);
      var B = numeric.dot(numeric.inv(M), C);
      B.splice(0, 0, sampledPoints[0]);
      B.push(sampledPoints[sampledPoints.length - 1]);
      var beziers = convertBSplineControlPointsToBezierControlPoints(B);
      return beziers;

    }

    function SignaturePad(selector) {
      var context = document.querySelector(selector),
        canvas = context.querySelectorAll('canvas'),
        element = canvas[0],
        canvasContext = null,
        previous = { 'x': null, 'y': null },
        output = [],
        mouseLeaveTimeout = false,
        mouseButtonDown = false,
        touchable = false,
        eventsBound = false,
        strokePoints = [];

      function clearMouseLeaveTimeout() {
        clearTimeout(mouseLeaveTimeout);
        mouseLeaveTimeout = false;
        mouseButtonDown = false;
      }

      function drawLine(e, newYOffset) {
        var offset, newX, newY;
        e.preventDefault();
        var target = e.target;
        offset = target.getBoundingClientRect();
        console.log(offset);
        console.log(e);
        clearTimeout(mouseLeaveTimeout);
        mouseLeaveTimeout = false;
        if (typeof e.changedTouches !== 'undefined') {
          newX = Math.floor(e.changedTouches[0].pageX - offset.left);
          newY = Math.floor(e.changedTouches[0].pageY - offset.top);
        } else {
          newX = Math.floor(e.pageX - offset.left);
          newY = Math.floor(e.pageY - offset.top);
        }

        if (previous.x === newX && previous.y === newY) {
          return true;
        }

        if (previous.x === null) {
          previous.x = newX;
        }

        if (previous.y === null) {
          previous.y = newY;
        }

        if (newYOffset) {
          newY += newYOffset;
        }

        canvasContext.beginPath();
        canvasContext.moveTo(previous.x, previous.y);
        canvasContext.lineTo(newX, newY);
        canvasContext.lineCap = 'round';
        canvasContext.stroke();
        canvasContext.closePath();
        strokePoints.push({
          'lx': newX, 'ly': newY,
          'mx': previous.x, 'my': previous.y
        });

        var maxCacheLength = 16;
        if (strokePoints.length >= maxCacheLength) {
          var retrace = output.slice(output.length - maxCacheLength + 2, output.length);
          canvasContext.strokeStyle = '#fff';
          for (var i in retrace) {
            var point = retrace[i];
            canvasContext.beginPath();
            canvasContext.moveTo(point.mx, point.my);
            canvasContext.lineTo(point.lx, point.ly);
            canvasContext.lineCap = 'round';
            canvasContext.stroke();
            canvasContext.closePath();
          }
          canvasContext.strokeStyle = '#000';
          strokePath(strokePoints, canvasContext);
          strokePoints = strokePoints.slice(maxCacheLength - 1, maxCacheLength);
        }
        output.push({
          'lx': newX,
          'ly': newY,
          'mx': previous.x,
          'my': previous.y
        });

        previous.x = newX;
        previous.y = newY;
      }

      function stopDrawing(e) {

        if (!!e && !(e.type === "touchend" || e.type == "touchcancel")) {
          drawLine(e, 1);
        } else {
          if (touchable) {
            canvas[0].removeEventListener('touchmove', onMouseMove);
          } else {
            canvas[0].removeEventListener('mousemove', onMouseMove);
          }

          if (output.length > 0) {
            if (output.length < 15) {

              that.props.setSign('');
              that.props.setHasError(true);
              that.setState({
                hasError: true
              });
            } else {

              var data_sign = getSignatureImage();
              const payload = {
                "signatureSrc": `${data_sign}`
              };
              postHttpRequest('api/signatures/verify', payload)
                .then(function (res) {
                  console.log(res.data);
                });
              that.props.setSign(data_sign);
              that.props.setHasError(false);
              that.setState({
                hasError: false
              });
            }
            strokePoints = [];
            resetCanvas();
            drawSignature(output, canvasContext, false);
          }
        }

        previous.x = null;
        previous.y = null;

        if (output.length > 0) {
          var outputElement = context.querySelector('.output');
          that.props.setSignOutPut(output);
          outputElement.value = JSON.stringify(output);
        }
      }

      function drawSigLine() {
        canvasContext.beginPath();
        canvasContext.lineWidth = 2;
        canvasContext.strokeStyle = '#ffffff00';
        canvasContext.moveTo(5, 200);
        canvasContext.lineTo(element.width - 5, 200);
        canvasContext.stroke();
        canvasContext.closePath();
      }

      function resetCanvas() {
        canvasContext.clearRect(0, 0, element.width, element.height);
        canvasContext.fillStyle = '#fff';
        canvasContext.fillRect(0, 0, element.width, element.height);
        drawSigLine();
        canvasContext.lineWidth = 10;
        canvasContext.strokeStyle = '#000';
      }

      function clearCanvas() {
        resetCanvas();
        context.querySelector('.output').value = '';
        that.props.setSign('');
        that.props.setSignOutPut('');
        output = [];
        stopDrawing();
      }

      function onMouseMove(e, o) {
        if (previous.x == null) {
          drawLine(e, 1);
        } else {
          drawLine(e, o);
        }
      }

      function startDrawing(e, touchObject) {
        if (touchable) {
          touchObject.addEventListener('touchmove', onMouseMove, false);
        } else {
          canvas[0].addEventListener('mousemove', onMouseMove);
        }
        drawLine(e, 1);
      }

      function initDrawEvents(e) {
        if (eventsBound) {
          return false;
        }

        eventsBound = true;
        if (typeof e.changedTouches !== 'undefined') {
          touchable = true;
        }

        if (touchable) {
          canvas[0].addEventListener('touchend', stopDrawing, false);
          canvas[0].addEventListener('touchcancel', stopDrawing, false);
        } else {
          canvas[0].addEventListener('mouseup', function (event) {
            if (mouseButtonDown) {
              stopDrawing();
              clearMouseLeaveTimeout();
            }
          });

          canvas[0].addEventListener('mouseleave', function (e) {
            if (mouseButtonDown) {
              stopDrawing(e);
            }

            if (mouseButtonDown && !mouseLeaveTimeout) {
              mouseLeaveTimeout = setTimeout(function () {
                stopDrawing();
                clearMouseLeaveTimeout();
              }, 500);
            }
          });

          canvas[0].ontouchstart = null;
        }
      }

      function drawIt() {
        clearCanvas();

        canvas[0].ontouchstart = function (e) {
          e.preventDefault();
          mouseButtonDown = true;
          initDrawEvents(e);
          startDrawing(e, this);
        };

        canvas[0].addEventListener('mousedown', function (e) {
          e.preventDefault();
          mouseButtonDown = true;
          initDrawEvents(e);
          startDrawing(e, this);
        });

        var clearButton = context.querySelector('.clearButton');
        clearButton.addEventListener('click', function (event) {
          event.preventDefault();
          clearCanvas();
        });
      }

      function strokePath(paths, context) {
        var showSampledPoints = true;
        var section = [];
        var sections = [];
        for (var i = 0; i < paths.length - 1; i++) {
          if (typeof (paths[i]) === 'object' && typeof (paths[i + 1]) === 'object') {
            var source = paths[i];
            var destination = paths[i + 1];

            if (source.mx == source.lx && source.my == source.ly) {
              continue;
            } else {
              section.push(source);
            }

            if (!(source.lx == destination.mx && source.ly == destination.my) &&
              !(source.mx == destination.lx && source.my == destination.ly)) {
              sections.push(section);
              section = [];
            }

            if (i == paths.length - 2) {
              section.push(destination);
              sections.push(section);
            }
          }
        }

        var lengths = [];
        for (var k = 0; k < sections.length; k++) {
          var lastPoint = sections[k].pop();
          sections[k] = sections[k].filter(function (element, index) { return index % 4 == 0; });
          sections[k].push(lastPoint);

          var section = sections[k];
          for (var j = 0; j < section.length; j++) {
            var point = section[j];
            var length = Math.abs(point.lx - point.mx) + Math.abs(point.ly - point.my);
            lengths.push(length);
          }
        }
        var signatureStats = stats(lengths);
        signatureStats.length = numeric.sum(lengths);
        signatureStats.mean *= 3;
        signatureStats.deviation *= 3;

        for (var k = 0; k < sections.length; k++) {
          var section = sections[k];
          var simpleTuples = section.map(function (n) { return [n.lx, n.ly]; });
          var beziers = getBezierControlPoints(simpleTuples);

          for (var i in beziers) {
            var p0 = beziers[i][0],
              p1 = beziers[i][1],
              p2 = beziers[i][2],
              p3 = beziers[i][3];

            var bezierSegmentLength = (
              Math.abs(p0[0] - p1[0]) +
              Math.abs(p1[0] - p2[0]) +
              Math.abs(p2[0] - p3[0]) +
              Math.abs(p0[1] - p1[1]) +
              Math.abs(p1[1] - p2[1]) +
              Math.abs(p2[1] - p3[1])
            );
            var zscore = (bezierSegmentLength - signatureStats.mean) / signatureStats.deviation;
            var width;
            if (zscore > 0) {
              width = 3 - zscore / 2.5;
            } else if (zscore <= 0) {
              width = 3 - zscore * 2;
            }

            if (showSampledPoints === true) {
              var pixelSize = 2;
              context.fillStyle = '#00000000';
              context.fillRect(p0[0], p0[1], pixelSize, pixelSize);
              context.fillRect(p3[0], p3[1], pixelSize, pixelSize);
            }
            context.beginPath();
            context.strokeStyle = "#101010";
            context.moveTo(p0[0], p0[1]);
            context.bezierCurveTo(
              p1[0], p1[1],
              p2[0], p2[1],
              p3[0], p3[1]
            );
            context.lineWidth = 10;
            context.lineWidth = width;
            context.lineCap = 'round';
            context.stroke();
            context.closePath();
          }
        }
      }

      function drawSignature(paths, context, saveOutput) {
        context.scale.apply(context, [1, 1]);
        for (var i in paths) {
          if (typeof paths[i] === 'object') {
            if (saveOutput) {
              output.push({
                'lx': paths[i].lx,
                'ly': paths[i].ly,
                'mx': paths[i].mx,
                'my': paths[i].my
              });
            }
          }
        }
        strokePath(paths, context);
      }

      function init() {

        var userAgent = navigator.userAgent;
        var versionMatch = (/CPU.+OS ([0-9_]{3}).*AppleWebkit.*Mobile/i.exec(userAgent)) || [0, '4_2'];
        var iOSVersion = parseFloat(versionMatch[1].replace('_', '.'));

        if (iOSVersion < 4.1) {
          Element.prototype.Oldoffset = Element.prototype.offset;
          Element.prototype.offset = function () {
            var result = this.Oldoffset();
            result.top -= window.scrollY;
            result.left -= window.scrollX;
            return result;
          };
        }

        if (!element.getContext && FlashCanvas) {
          FlashCanvas.initElement(element);
        }

        if (element.getContext) {
          canvasContext = element.getContext('2d');
          drawIt();
        }
        if (that.props.sign && that.props.signOutPut.length > 0) {
          var old_output = that.props.signOutPut;
          drawSignature(old_output, canvasContext, true);
          that.props.setSign(old_output);
          that.props.setHasError(false);
          that.setState({
            hasError: false
          });
        }
      }

      function getSignatureImage() {
        var tmpCanvas = document.createElement('canvas'),
          tmpContext = null,
          data = null;

        tmpCanvas.style.position = 'absolute';
        tmpCanvas.style.top = '-999em';
        tmpCanvas.width = element.width;
        tmpCanvas.height = element.height;
        document.body.appendChild(tmpCanvas);

        if (!tmpCanvas.getContext && FlashCanvas) {
          FlashCanvas.initElement(tmpCanvas);
        }

        tmpContext = tmpCanvas.getContext('2d');
        var img = context.querySelector(".reapeaterimg");
        var pat = tmpContext.createPattern(img, 'repeat');
        tmpContext.fillStyle = pat;
        tmpContext.fillRect(0, 0, element.width, element.height);
        tmpContext.lineWidth = 10;
        tmpContext.strokeStyle = '#000';
        drawSignature(output, tmpContext, false);
        data = tmpCanvas.toDataURL.apply(tmpCanvas, arguments);
        document.body.removeChild(tmpCanvas);
        tmpCanvas = null;
        return data;
      }

      init();
    }

    var sigPadElement = document.querySelector('#' + this.props.padId);
    var sigPadCanvas = sigPadElement.querySelector('canvas');
    var sig_p_L = sigPadElement.offsetWidth;
    var sig_ph_L = sigPadElement.offsetHeight;
    sigPadCanvas.width = sig_p_L;
    sigPadCanvas.height = sig_ph_L;
    if (this.props.padId) {
      new SignaturePad('#' + this.props.padId);
    }
    setTimeout(function () {
      that.setState({ showSignImg: false });
    }, 6000);
  }

  componentDidUpdate(prevProps) {
    if (this.props.hasError !== prevProps.hasError) {
      this.setState({ hasError: this.props.hasError });
    }
  }


  render() {
    return (
      <div id={this?.props?.padId} className={this?.props?.hasError ? "signature_pad_wrapper has-error" : "signature_pad_wrapper"} >
        <div className="canvas_wrapper">
          <canvas />
        </div>
        <button type="button" className="btn btn-danger clearButton">Clear</button>
        <input className="output" type="hidden" />
        {this.state.hasError && <p className='error'>Please provide valid sign.</p>}
        <img src={repeter} className="reapeaterimg" />
        <img src={signHere} className="signHere_img" />
        {this.state.showSignImg && <img src={signatuerImg} onClick={() => { this.setState({ showSignImg: false }) }} className="signatuerImg" />}
      </div>
    );
  }
}