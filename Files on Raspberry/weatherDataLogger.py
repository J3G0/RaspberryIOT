import os
import threading
import urllib2
from sense_hat import SenseHat

sense = SenseHat()

def readTemp():

	global temperature
	global cpu_temp
	global humidity
	global pressure

	cpu_temp = 0
	temperature = 0
	humidity = 0
	pressure = 0
	
	temperature = sense.get_temperature()
	
def readHum():

	global temperature
	global cpu_temp
	global humidity
	global pressure

	cpu_temp = 0
	temperature = 0
	humidity = 0
	pressure = 0
	
	humidity = sense.get_humidity() + 10

	humidity = round(humidity,1)
	
def readPress():

	global temperature
	global cpu_temp
	global humidity
	global pressure

	cpu_temp = 0
	temperature = 0
	humidity = 0
	pressure = 0

	pressure = 0

	pressure = sense.get_pressure()
	pressure = sense.get_pressure()
	pressure = round(pressure,1)
	

	
def readCPUTemperature():	

	global temperature
	global cpu_temp

	cpu_temp = os.popen("/opt/vc/bin/vcgencmd measure_temp").read()
	cpu_temp = cpu_temp[:-3]
	cpu_temp = cpu_temp[5:]
	
	temperature = sense.get_temperature()

	print(cpu_temp)

	temperature = temperature - 8.5


def sendTempToServer():
	global temperature
	global pressure
	global humidity
	global cpu_temp

	threading.Timer(300,sendTempToServer).start()
	print("Sensing...")
	readTemp()
	readCPUTemperature()
	temperature = round(temperature,1)
	cpu_temp= "%.1f" % float(cpu_temp)
	
	# str() converts the value to a string
	message = "Temperature: " + str(temperature) + " C" + " CPU Temperature " + str(cpu_temp) + " C"
	# Display the scrolling message
	sense.show_message(message, scroll_speed=0.1)
	
	print(temperature)
	print(humidity)
	print(pressure)
	temp= "%.1f" %temperature
	cpu_temp= "%.1f" % float(cpu_temp)
	hum ="%.1f" %humidity
	press = "%.1f" %pressure
	urllib2.urlopen("URL"+temp+"&cputemp="+cpu_temp+"&hum="+hum+"&pr="+press).read()
	
def sendHumToServer():
	global temperature
	global pressure
	global humidity
	global cpu_temp

	threading.Timer(600,sendHumToServer).start()
	print("Sensing...")
	readHum()
	temperature = round(temperature,1)
	
	# str() converts the value to a string
	message = "humidity: " + str(humidity) + " %"
	# Display the scrolling message
	sense.show_message(message, scroll_speed=0.01)
	
	print(temperature)
	print(cpu_temp)
	print(humidity)
	print(pressure)
	temp= "%.1f" %temperature
	cpu_temp = "%.1f" %cpu_temp
	hum ="%.1f" %humidity
	press = "%.1f" %pressure
	urllib2.urlopen("URL"+temp+"&cputemp="+cpu_temp+"&hum="+hum+"&pr="+press).read()

def sendPressToServer():
	global temperature
	global pressure
	global humidity
	global cpu_temp

	threading.Timer(900,sendPressToServer).start()
	print("Sensing...")
	readPress()
	temperature = round(temperature,1)
	
	# str() converts the value to a string
	message = "pressure: " + str(pressure) + " hPa"
	# Display the scrolling message
	sense.show_message(message, scroll_speed=0.01)
	
	print(temperature)
	print(cpu_temp)
	print(humidity)
	print(pressure)
	temp= "%.1f" %temperature
	cpu_temp = "%.1f" %cpu_temp
	hum ="%.1f" %humidity
	press = "%.1f" %pressure
	urllib2.urlopen("URL"+temp+"&cputemp="+cpu_temp+"&hum="+hum+"&pr="+press).read()
	
	

sendTempToServer()
sendHumToServer()
sendPressToServer()
