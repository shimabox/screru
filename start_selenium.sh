#!/bin/bash

##################################################
# Start Xvfb (virtual display), selenium.
# The default size is 1366 x 768 x 24.
##################################################

# Start Xvfb (virtual display).
# Please change size (width x height x depth) if you want to change it.
sudo Xvfb :1 -screen 0 1366x768x24 &

# :1 display.
export DISPLAY=:1

# Start selenium.
java -jar selenium-server-standalone-3.8.1.jar -enablePassThrough false &
